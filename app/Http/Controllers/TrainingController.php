<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\User;
use App\Models\Event;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with('trainer.sport')->orderBy('date', 'desc')->paginate(10);
        return view('trainings.index', compact('trainings'));
    }

    public function view(Request $request)
    {
        $query = Training::with('trainer.sport')
            ->orderBy('date', 'desc');

        if (
            Auth::check() &&
            Auth::user()->role_id == 3 &&
            $request->get('filter') === 'my'
        ) {
            $sportId = Auth::user()->sport_id;

            $query->whereHas('trainer', function ($q) use ($sportId) {
                $q->where('sport_id', $sportId);
            });
        }

        $trainings = $query->paginate(10)->withQueryString();

        return view('trainings.view', compact('trainings'));
    }

    // ---------------------------
    // TWORZENIE TRENINGU
    // ---------------------------
    public function create()
    {
        if (Auth::user()->role_id == 2) { // Trener
            $trainer = Auth::user();
            $sport = $trainer->sport;

            if (!$sport || !$sport->is_active) {
                return redirect()->route('trainer.trainings')
                    ->withErrors(['sport' => 'Nie możesz dodać treningu – Twoja dyscyplina jest zarchiwizowana.']);
            }

            return view('trainings.create', compact('trainer'));
        }

        // Admin – lista tylko aktywnych trenerów z aktywnymi dyscyplinami
        $trainers = User::with('sport')
            ->where('role_id', 2)
            ->where('is_active', true)
            ->whereHas('sport', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        return view('trainings.create', compact('trainers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string|max:500',
            'date' => 'required|date|after_or_equal:2024-01-01',
            'start_time' => 'required',
            'end_time' => ['required', 'after:start_time'],
            'trainer_id' => 'required|exists:users,user_id',
            'max_points' => 'nullable|integer|min:0|max:200',
        ]);

        // Pobieramy trenera
        $trainer = User::findOrFail($validatedData['trainer_id']);

        // Sprawdzenie aktywności dyscypliny
        if (!$trainer->sport || !$trainer->sport->is_active) {
            return redirect()->back()
                ->withErrors(['trainer_id' => 'Nie możesz dodać treningu – dyscyplina trenera jest zarchiwizowana.'])
                ->withInput();
        }

        // Sprawdzenie konfliktów
        if ($this->checkDateConflict($validatedData['date'])) {
            return redirect()->back()->withErrors(['date' => 'W tym dniu jest już zaplanowane wydarzenie.'])->withInput();
        }

        if ($this->hasTimeConflict(
            $validatedData['trainer_id'],
            $validatedData['date'],
            $validatedData['start_time'],
            $validatedData['end_time']
        )) {
            return redirect()->back()->withErrors(['start_time' => 'Ten trener ma już trening w tym czasie.'])->withInput();
        }

        Training::create($validatedData);
        return redirect()->route('admin.trainings.index')->with('success', 'Trening został dodany pomyślnie.');
    }

    public function edit($training_id)
    {
        $training = Training::findOrFail($training_id);

        if (Auth::user()->role_id == 2) { // Trener
            if (!$training->trainer->sport || !$training->trainer->sport->is_active) {
                return redirect()->route('trainer.trainings')
                    ->withErrors(['sport' => 'Nie możesz edytować treningu – dyscyplina jest zarchiwizowana.']);
            }

            return view('trainings.edit', compact('training'));
        }

        // Admin – lista tylko aktywnych trenerów z aktywnymi dyscyplinami
        $trainers = User::with('sport')
            ->where('role_id', 2)
            ->where('is_active', true)
            ->whereHas('sport', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        return view('trainings.edit', compact('training', 'trainers'));
    }

    public function update(Request $request, $training_id)
    {
        $validatedData = $request->validate([
            'description' => 'required|string|max:500',
            'date' => 'required|date|after_or_equal:2024-01-01',
            'start_time' => 'required',
            'end_time' => ['required', 'after:start_time'],
            'trainer_id' => 'required|exists:users,user_id',
            'max_points' => 'nullable|integer|min:0|max:200',
        ]);

        $training = Training::findOrFail($training_id);

        $trainer = User::findOrFail($validatedData['trainer_id']);

        if (!$trainer->sport || !$trainer->sport->is_active) {
            return redirect()->back()
                ->withErrors(['trainer_id' => 'Nie możesz zaktualizować treningu – dyscyplina trenera jest zarchiwizowana.'])
                ->withInput();
        }

        if ($this->hasTimeConflict(
            $validatedData['trainer_id'],
            $validatedData['date'],
            $validatedData['start_time'],
            $validatedData['end_time'],
            $training->training_id
        )) {
            return redirect()->back()
                ->withErrors(['start_time' => 'Ten trener ma już trening w tym czasie.'])
                ->withInput();
        }

        $training->update($validatedData);

        return redirect()->route('admin.trainings.index')->with('success', 'Trening został zaktualizowany pomyślnie.');
    }

    public function destroy($training_id)
    {
        $training = Training::findOrFail($training_id);
        $training->delete();
        return redirect()->route('admin.trainings.index')->with('success', 'Trening został usunięty pomyślnie.');
    }

    // ---------------------------
    // ZAPIS SPORTOWCA
    // ---------------------------
    public function signUp($training_id)
    {
        $user = Auth::user();
        $training = Training::findOrFail($training_id);

        if (!$this->canSignUpForTraining($user, $training)) {
            return redirect()->route('trainings.view')->with('error', 'Nie możesz zapisać się na ten trening.');
        }

        if ($user->trainings()->where('training_user.training_id', $training_id)->exists()) {
            return redirect()->route('trainings.view')->with('error', 'Jesteś już zapisany na ten trening.');
        }

        $user->trainings()->attach($training_id);
        return redirect()->route('trainings.view')->with('success', 'Zapisano na trening.');
    }

    private function canSignUpForTraining($user, $training)
    {
        if (!$user->is_active) {
            return false;
        }

        $now = Carbon::now();
        $trainingStart = Carbon::parse($training->date)->startOfDay();

        return $user->role_id == 3 && $user->sport_id == $training->trainer->sport->sport_id && $now->lt($trainingStart);
    }

    private function checkDateConflict($date)
    {
        return Event::where('date', $date)->exists();
    }

    private function hasTimeConflict($trainer_id, $date, $start_time, $end_time, $excludeTrainingId = null)
    {
        $query = Training::where('trainer_id', $trainer_id)
                         ->where('date', $date);

        if ($excludeTrainingId) {
            $query->where('training_id', '!=', $excludeTrainingId);
        }

        $trainings = $query->get();

        foreach ($trainings as $training) {
            if (
                ($start_time >= $training->start_time && $start_time < $training->end_time) ||
                ($end_time > $training->start_time && $end_time <= $training->end_time) ||
                ($start_time <= $training->start_time && $end_time >= $training->end_time)
            ) {
                return true; // konflikt czasowy
            }
        }

        return false; // brak konfliktu
    }

    public function participants($training_id)
    {
        $training = Training::findOrFail($training_id);

        $participants = \App\Models\TrainingUser::with('user')
            ->where('training_id', $training_id)
            ->paginate(20);

        return view('trainings.participants', compact('training', 'participants'));
    }
}

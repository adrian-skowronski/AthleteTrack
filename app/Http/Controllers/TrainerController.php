<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\User;
use App\Models\TrainingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TrainerController extends Controller
{
    // ------------------------------------------------------------------------
    // Panel trenera - profil + lista treningów
    // ------------------------------------------------------------------------
    public function profile()
    {
        $user = Auth::user();
        $age = Carbon::parse($user->birthdate)->age;

        $trainings = Training::where('trainer_id', $user->user_id)
            ->orderBy('date', 'desc')
            ->paginate(5);

        return view('trainer.profile', compact('user', 'age', 'trainings'));
    }

    public function trainings()
    {
        $trainer = Auth::user();

        $trainings = Training::where('trainer_id', $trainer->user_id)
            ->orderBy('date', 'desc')
            ->paginate(5);

        return view('trainer.trainings', compact('trainings'));
    }

    // ------------------------------------------------------------------------
    // Uczestnicy treningu
    // ------------------------------------------------------------------------
    public function viewParticipants($training_id)
    {
        $training = Training::findOrFail($training_id);

        $users = $training->users()->withPivot('points', 'status')->paginate(10);

        return view('trainer.participants', compact('training', 'users'));
    }

    // ------------------------------------------------------------------------
    // Edycja statusu uczestnika
    // ------------------------------------------------------------------------
    public function editStatus($training_id, $user_id)
    {
        $trainingUser = TrainingUser::where('training_id', $training_id)
            ->where('user_id', $user_id)
            ->firstOrFail();

        $training = Training::findOrFail($training_id);
        $user = User::findOrFail($user_id);
        $maxPoints = $training->max_points;

        return view('trainer.editStatus', compact('training', 'user', 'maxPoints', 'trainingUser'));
    }

    public function updateStatus(Request $request, $training_id, $user_id)
    {
        $training = Training::findOrFail($training_id);
        $maxPoints = $training->max_points;

        $statusRules = $request->status === 'obecność'
            ? 'required|in:obecność'
            : 'required|in:nieobecność usprawiedliwiona,nieobecność nieusprawiedliwiona';

        $validatedData = $request->validate([
            'status' => $statusRules,
            'points' => [
                'required',
                'integer',
                'min:' . ($request->status === 'obecność' ? 0 : -5),
                'max:' . $maxPoints,
            ],
        ]);

        $trainingUser = TrainingUser::where('training_id', $training_id)
            ->where('user_id', $user_id)
            ->firstOrFail();

        // Obliczamy nowe punkty
        $newPoints = $validatedData['points'];
        if ($validatedData['status'] == 'nieobecność usprawiedliwiona') {
            $newPoints = 0;
        } elseif ($validatedData['status'] == 'nieobecność nieusprawiedliwiona') {
            $newPoints = -5;
        }

        // Synchronizujemy punkty użytkownika
        $user = User::findOrFail($user_id);
        $oldPoints = $trainingUser->points ?? 0;
        $user->points = $user->points - $oldPoints + $newPoints;
        $user->save();

        $trainingUser->update([
            'status' => $validatedData['status'],
            'points' => $newPoints,
        ]);

        return redirect()->route('trainer.participants', $training_id)
            ->with('success', 'Status uczestnika zaktualizowany.');
    }

    // ------------------------------------------------------------------------
    // Zarządzanie treningami
    // ------------------------------------------------------------------------
    public function createTraining()
    {
        return view('trainer.createTraining');
    }

    public function storeTraining(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:500',
            'date' => 'required|date|after_or_equal:2024-01-01',
            'start_time' => 'required|date_format:H:i',
            'end_time' => ['required','date_format:H:i','after:start_time'],
            'max_points' => 'nullable|integer|min:0|max:200',
        ]);

        $trainer_id = Auth::id();

        if ($this->hasTimeConflict($trainer_id, $request->date, $request->start_time, $request->end_time)) {
            return redirect()->back()
                ->withErrors(['start_time' => 'Ten trener ma już trening w tym czasie.'])
                ->withInput();
        }

        Training::create([
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'max_points' => $request->max_points,
            'trainer_id' => $trainer_id,
        ]);

        return redirect()->route('trainer.trainings')
            ->with('success', 'Trening został dodany pomyślnie.');
    }


public function show($user_id)
{
    $trainer = User::where('user_id', $user_id)
                   ->where('role_id', 2) 
                   ->firstOrFail();

    return view('trainer.details', compact('trainer'));
}


    public function trainingEdit($training_id)
    {
        $training = Training::findOrFail($training_id);
        return view('trainer.editTraining', compact('training'));
    }

    public function trainingUpdate(Request $request, $training_id)
    {
        $validatedData = $request->validate([
            'description' => 'required|string|max:500',
            'date' => 'required|date|after_or_equal:2024-01-01',
            'start_time' => 'required',
            'end_time' => ['required','after:start_time'],
            'max_points' => 'nullable|integer|min:0|max:200',
        ]);

        $trainer_id = Auth::id();
        $currentTraining = Training::findOrFail($training_id);

        if ($this->hasTimeConflict(
            $trainer_id,
            $validatedData['date'],
            $validatedData['start_time'],
            $validatedData['end_time'],
            $currentTraining->training_id
        )) {
            return redirect()->back()
                ->withErrors(['start_time' => 'Ten trener ma już trening w tym czasie.'])
                ->withInput();
        }

        $currentTraining->update($validatedData);

        return redirect()->route('trainer.trainings')
            ->with('success', 'Trening został zaktualizowany.');
    }

    public function trainingDestroy($training_id)
    {
        $training = Training::findOrFail($training_id);
        $training->delete();

        return redirect()->route('trainer.trainings')
            ->with('success', 'Trening został usunięty.');
    }

    // ------------------------------------------------------------------------
    // Usuwanie uczestnika z treningu
    // ------------------------------------------------------------------------
    public function removeParticipant($training_id, $user_id)
    {
        $participant = TrainingUser::where('training_id', $training_id)
            ->where('user_id', $user_id)
            ->firstOrFail();
        $participant->delete();

        return redirect()->route('trainer.participants', $training_id)
            ->with('success', 'Uczestnik został wypisany z treningu.');
    }

    // ------------------------------------------------------------------------
    // Edycja danych trenera
    // ------------------------------------------------------------------------
    public function edit()
    {
        $user = Auth::user();
        return view('trainer.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required','string','regex:/^[A-ZĄĆĘŁŃÓŚŻŹ][a-ząćęłńóśżź]{1,}(?:\s[A-ZĄĆĘŁŃÓŚŻŹ][a-ząćęłńóśżź]{1,})?$/u','max:80'],
            'surname' => ['required','string','regex:/^[A-ZĄĆĘŁŃÓŚŻŹ][a-ząćęłńóśżź]{1,}$/u','max:80'],
            'birthdate' => 'required|date|after_or_equal:1920-01-01',
            'phone' => ['required','regex:/^[0-9]{9}$/'],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $user->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'birthdate' => $request->birthdate,
            'phone' => $request->phone,
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('upload/images', 'public');
            $user->photo = $path;
            $user->save();
        }

        return redirect()->route('trainer.profile')
            ->with('success', 'Dane zostały zaktualizowane.');
    }

    // ------------------------------------------------------------------------
    // Zmiana hasła trenera
    // ------------------------------------------------------------------------
    public function showChangePasswordForm()
    {
        return view('trainer.change_password');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Obecne hasło jest nieprawidłowe.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('trainer.profile')
            ->with('success', 'Hasło zostało zmienione pomyślnie.');
    }

    // ------------------------------------------------------------------------
    // Pomocnicze
    // ------------------------------------------------------------------------
    private function hasTimeConflict($trainer_id, $date, $start_time, $end_time, $excludeTrainingId = null)
    {
        $query = Training::where('trainer_id', $trainer_id)->where('date', $date);

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
                return true;
            }
        }

        return false;
    }
}

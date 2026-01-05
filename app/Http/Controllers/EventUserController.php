<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventUser;
use App\Models\User;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class EventUserController extends Controller
{
    // -------------------------------------------------
    // (opcjonalne) lista wszystkich przypisań
    // -------------------------------------------------
    public function index()
    {
        $eventUsers = EventUser::with('event', 'user')->paginate(10);
        return view('event-user.index', compact('eventUsers'));
    }

    // -------------------------------------------------
    // Widok uczestników JEDNEGO wydarzenia
    // -------------------------------------------------
    public function show($event_id)
    {
        $event = Event::withCount('users')->findOrFail($event_id);

        $eventUsers = EventUser::with('user')
            ->where('event_id', $event_id)
            ->paginate(10);

        return view('event-user.show', compact('event', 'eventUsers'));
    }

    // -------------------------------------------------
    // Edycja punktów uczestnika
    // -------------------------------------------------
    public function edit($event_user_id)
    {
        $eventUser = EventUser::findOrFail($event_user_id);

        if (Gate::denies('assign-points', $eventUser)) {
            return redirect()
                ->route('admin.event-user.show', $eventUser->event_id)
                ->with('error', 'Nie masz uprawnień do przypisania punktów przed dniem wydarzenia.');
        }

        return view('event-user.edit', compact('eventUser'));
    }

    // -------------------------------------------------
    // Aktualizacja punktów
    // -------------------------------------------------
    public function update(Request $request, $event_user_id)
    {
        $eventUser = EventUser::findOrFail($event_user_id);
        $user = User::findOrFail($eventUser->user_id);

        $request->validate([
            'points' => 'required|integer|in:0,5,10,20,30,40',
        ]);

        $user->points += $request->points;
        $user->save();
        $user->updateCategoryByPoints();

        $eventUser->update([
            'points' => $request->points,
        ]);

        return redirect()
            ->route('admin.event-user.show', $eventUser->event_id)
            ->with('success', 'Punkty zaktualizowane.');
    }

    // -------------------------------------------------
    // Usunięcie uczestnika z wydarzenia
    // -------------------------------------------------
  public function destroy($event_user_id)
{
    $eventUser = EventUser::findOrFail($event_user_id);

    if (Gate::denies('remove-athlete', $eventUser)) {
        return redirect()
            ->route('admin.event-user.show', $eventUser->event_id)
            ->with('error', 'Akcja niedostępna.');
    }

    EventUser::where('event_id', $eventUser->event_id)
        ->where('user_id', $eventUser->user_id)
        ->delete();

    return redirect()
        ->route('admin.event-user.show', $eventUser->event_id)
        ->with('success', 'Zawodnik został poprawnie wypisany.');
}


    // -------------------------------------------------
    // Wybór zawodników do wydarzenia
    // -------------------------------------------------
    public function selectAthletes($event_id)
    {
        $event = Event::findOrFail($event_id);

        $minBirthdate = Carbon::now()->subYears($event->age_to)->startOfDay();
        $maxBirthdate = Carbon::now()->subYears($event->age_from)->endOfDay();

        $athletes = User::where('role_id', 3)
            ->where('is_active', true)
            ->where('category_id', '>=', $event->required_category_id)
            ->whereBetween('birthdate', [$minBirthdate, $maxBirthdate])
            ->get();

        return view('event-user.select_athletes', compact('athletes', 'event'));
    }

    // -------------------------------------------------
    // Zapis zawodników na wydarzenie
    // -------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,event_id',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,user_id',
        ]);

        $event = Event::findOrFail($request->event_id);

        $currentParticipants = EventUser::where('event_id', $event->event_id)->count();

        if (now()->gte(Carbon::parse($event->date)->startOfDay())) {
            return redirect()
                ->route('admin.event-user.show', $event->event_id)
                ->with('error', 'Minął czas na zapisanie się na wydarzenie.');
        }

        if ($currentParticipants + count($request->user_id) > $event->max_participants) {
            return redirect()
                ->route('admin.event-user.show', $event->event_id)
                ->with('error', 'Brak miejsc na wydarzenie.');
        }

        foreach ($request->user_id as $userId) {

            $user = User::findOrFail($userId);

            if (!$user->is_active) {
                return redirect()
                    ->route('admin.event-user.show', $event->event_id)
                    ->with('error', "Zawodnik {$user->name} {$user->surname} jest zarchiwizowany.");
            }

            $age = Carbon::parse($user->birthdate)->age;
            if ($age < $event->age_from || $age > $event->age_to) {
                return redirect()
                    ->route('admin.event-user.show', $event->event_id)
                    ->with('error', 'Zawodnik nie spełnia wymagań wiekowych.');
            }

            $conflict = EventUser::where('user_id', $userId)
                ->whereHas('event', fn ($q) => $q->whereDate('date', $event->date))
                ->exists();

            if ($conflict) {
                return redirect()
                    ->route('admin.event-user.show', $event->event_id)
                    ->with('error', 'Zawodnik ma inne zawody w tym dniu.');
            }

            EventUser::firstOrCreate([
                'event_id' => $event->event_id,
                'user_id' => $userId,
            ]);
        }

        return redirect()
            ->route('admin.event-user.show', $event->event_id)
            ->with('success', 'Zawodnicy zostali zapisani na wydarzenie.');
    }
}

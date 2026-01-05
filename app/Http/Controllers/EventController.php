<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Training;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
$events = Event::withCount('users')
    ->orderBy('date', 'desc')
    ->paginate(10);

        return view('events.index', compact('events'));
    }

    public function view()
    {
        $events = Event::orderBy('date','desc')->paginate(10);
        return view('events.view', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('events.create', compact ('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'required_category_id' => 'required|exists:categories,category_id',
            'age_from' => 'required|integer|min:0',
            'age_to' => 'required|integer|min:' . $request->input('age_from'),
            'description' => 'string|max:500',
            'date' => 'required|date|after_or_equal:2024-01-01',
            'start_hour' => 'required',
            'max_participants' => 'required|integer|min:3|max:299',
        ]);

        if ($this->checkDateConflict($validatedData['date'])) {
            return redirect()->back()->withErrors(['date' => 'W tym dniu jest już zaplanowany trening.'])->withInput();
        }

        Event::create($validatedData);
        return redirect()->route('admin.events.index');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit($event_id)
    {
        $categories = Category::all();
        $event = Event::findOrFail($event_id);
        return view('events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, $event_id)
    {
        $categories = Category::all();

        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'required_category_id' => 'required|exists:categories,category_id',
            'age_from' => 'required|integer|min:0',
            'age_to' => 'required|integer|min:' . $request->input('age_from'),
            'description' => 'string|max:500',
            'date' => 'required|date|after_or_equal:2024-01-01',
            'start_hour' => 'required',
            'max_participants' => 'required|integer|min:3|max:299',
        ]);

        if ($this->checkDateConflict($validatedData['date'])) {
            return redirect()->back()->withErrors(['date' => 'W tym dniu jest już zaplanowany trening.'])->withInput();
        }

        $event = Event::findOrFail($event_id);
        $event->update($validatedData);
        return redirect()->route('admin.events.index');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index') ->with('success', 'Wydarzenie zostało usunięte.');
    }

    private function checkDateConflict($date)
    {
        return Training::where('date', $date)->exists();
    }

    public function register(Request $request, $event_id)
    {
        $user = Auth::user();
        if (!$user->is_active) {
    return redirect()->back()->withErrors(['error' => 'Wybrany sportowiec jest zarchiwizowany i nie może być zapisany na wydarzenie.']);
}

        $event = Event::findOrFail($event_id);
    if ($user->category_id < $event->required_category_id) {
            return redirect()->back()->withErrors(['error' => 'Sportowiec nie spełnia wymagań kategorii dla tego wydarzenia.']);
        }

        if ($event->users()->where('user_id', $user->user_id)->exists()) {
            return redirect()->back()->withErrors(['error' => 'Sportowiec jest już zapisany na to wydarzenie.']);
        }
        $event->users()->attach($user->user_id);

        return redirect()->back()->with('success', 'Sportowiec został zapisany na wydarzenie.');
    }
}

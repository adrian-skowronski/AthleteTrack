<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sport;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

    $sports = Sport::when($filter === 'active', fn($q) => $q->where('is_active', true))
                   ->when($filter === 'inactive', fn($q) => $q->where('is_active', false))
                   ->get();

    return view('sports.index', compact('sports', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:sports',
        ]);
        
        Sport::create($request->all());
        return redirect()->route('admin.sports.index');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($sport_id)
    {
        $sport = Sport::findOrFail($sport_id);
        return view('sports.edit', compact('sport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $sport_id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:sports',
        ]);

        $sport = Sport::findOrFail($sport_id);
        $sport->update($request->all());

        return redirect()->route('admin.sports.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($sport_id)
    {
        $sport = Sport::findOrFail($sport_id);
        $sport->delete();
        return redirect()->route('admin.sports.index');
    }
    public function deactivate(Sport $sport)
{
    $sport->update(['is_active' => false]);
    return redirect()->route('admin.sports.index')->with('success', 'Dyscyplina została pomyślnie zarchiwizowana.');
}

public function activate(Sport $sport)
{
    $sport->update(['is_active' => true]);
    return redirect()->route('admin.sports.index')->with('success', 'Dyscyplina została pomyślnie przywrócona.');
}

}

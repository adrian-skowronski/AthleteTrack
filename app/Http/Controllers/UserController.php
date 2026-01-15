<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Sport;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = User::query();

        if ($filter === 'active') {
            $query->where('is_active', 1);
        } elseif ($filter === 'archived') {
            $query->where('is_active', 0);
        }

        $users = $query->paginate(10);

        $pendingUsers = User::where('approved', 0)->paginate(10);

        return view('admin.users.index', compact('users', 'pendingUsers', 'filter'));
    }

    public function create()
    {
        $roles = Role::all();
        $categories = Category::all();
    $sports = Sport::where('is_active', 1)->get(); // tylko aktywne
        return view('users.create', compact('roles', 'categories', 'sports'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'regex:/^[A-ZĄĆĘŁŃÓŚŻŹ][a-ząćęłńóśżź]{1,}(?:\s[A-ZĄĆĘŁŃÓŚŻŹ][a-ząćęłńóśżź]{1,})?$/u', 'max:80'],
            'surname' => ['required', 'string', 'regex:/^[A-Za-zĄĆĘŁŃÓŚŻŹąćęłńóśżź][a-ząćęłńóśżź]{1,}$/u', 'max:80'],
            'email' => 'required|email|unique:users|max:120',
            'password' => 'required|string|min:8|max:100',
            'birthdate' => 'required|date|after_or_equal:1920-01-01',
            'points' => 'nullable|integer',
            'phone' => 'required|string|max:11|min:9',
            'role_id' => 'nullable|exists:roles,role_id',
            'category_id' => 'nullable|exists:categories,category_id',
            'sport_id' => 'nullable|exists:sports,sport_id',
            'approved' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        $validatedData['password'] = bcrypt($request->password);
if ($request->sport_id) {
    $sport = Sport::find($request->sport_id);
    if (!$sport || !$sport->is_active) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['sport_id' => 'Wybrana dyscyplina jest zarchiwizowana i nie może zostać przypisana.']);
    }
}
        $user = User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Użytkownik został pomyślnie dodany.');
    }

    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        $roles = Role::all();
        $categories = Category::all();
    $sports = Sport::where('is_active', 1)->get(); // tylko aktywne
        return view('users.edit', compact('user', 'roles', 'categories', 'sports'));
    }

    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'email' => 'required|email|max:120',
            'password' => 'string|min:8|max:100',
            'birthdate' => 'required|date|after_or_equal:1920-01-01',
            'points' => 'nullable|integer',
            'phone' => 'required|string|max:11|min:9',
            'role_id' => 'nullable|exists:roles,role_id',
            'category_id' => 'nullable|exists:categories,category_id',
            'sport_id' => 'nullable|exists:sports,sport_id',
            'approved' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $photoPath = $request->file('photo')->store('photos', 'public');
            $validatedData['photo'] = $photoPath;
        }

        if (isset($validatedData['points'])) {
            $user->updateCategoryByPoints();
        }
        if ($request->sport_id) {
    $sport = Sport::find($request->sport_id);
    if (!$sport || !$sport->is_active) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['sport_id' => 'Wybrana dyscyplina jest zarchiwizowana i nie może zostać przypisana.']);
    }
}

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Dane użytkownika zostały pomyślnie zaktualizowane.');
    }

    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        if ($user->role_id === 1) {
            return redirect()->back()->with('error', 'Nie można usunąć użytkownika o roli Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Użytkownik został pomyślnie usunięty.');
    }

    public function deactivate(User $user)
    {
        
    // Nie pozwalaj archiwizować Adminów
    if ($user->role_id == 1) {
        return redirect()->route('admin.users.index')
                         ->with('error', 'Nie można zarchiwizować użytkownika o roli Admin.');
    }
        $futureTrainingsCount = $user->trainings()
            ->where('date', '>=', now())
            ->count();

        if ($futureTrainingsCount > 0) {
            session()->flash('warning', 'Uwaga: użytkownik ma zapisane przyszłe treningi.');
        }

        $user->update(['is_active' => false]);

        return redirect()->route('admin.users.index')->with('success', 'Użytkownik został pomyślnie zarchiwizowany.');
    }

    public function activate(User $user)
    {
        $user->update(['is_active' => true]);

        return redirect()->route('admin.users.index')->with('success', 'Użytkownik został pomyślnie przywrócony.');
    }
}

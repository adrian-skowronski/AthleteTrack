<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Role;
use App\Models\Sport;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $tables = ['trainings'];
        $users = User::where('approved', false)->get();
                $admin = Auth::user();



        return view('admin.index', compact('tables', 'users', 'admin'));
    }

    public function showTable($table)
    {
        if (!Schema::hasTable($table)) {
            return redirect()->route('admin.index')->with('error', 'Tabela nie istnieje.');
        }

        $records = DB::table($table)->get();

        return view('admin.table', compact('table', 'records'));
    }


    public function approve($user_id)
    {
        $user = User::findOrFail($user_id);
    $sports = Sport::where('is_active', true)->get();
        $roles = Role::all();
        $categories = Category::all();
        return view('admin.approve', compact('user', 'sports', 'roles', 'categories'));
    }

   public function storeApproval(Request $request, $user_id)
{
    $user = User::findOrFail($user_id);

    $role = Role::find($request->role_id);
    $roleName = strtolower($role->name ?? '');

    // Walidacja warunkowa
    $rules = [
        'role_id' => 'required|exists:roles,role_id',
    ];

    if ($roleName === 'sportowiec') {
        $rules['points'] = 'required|integer|min:0';
        $rules['sport_id'] = 'required|exists:sports,sport_id';
    } elseif ($roleName === 'trener') {
        $rules['sport_id'] = 'required|exists:sports,sport_id';
    }
    // Admin – nie wymagamy punktów ani dyscypliny

    $validated = $request->validate($rules);
if (($roleName === 'sportowiec' || $roleName === 'trener') && $request->sport_id) {
        $sport = Sport::find($request->sport_id);
        if (!$sport || !$sport->is_active) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['sport_id' => 'Wybrana dyscyplina jest zarchiwizowana i nie może zostać przypisana.']);
        }
    }
    $user->role_id = $request->role_id;

    if ($roleName === 'sportowiec') {
        $user->points = $request->points;
        $user->sport_id = $request->sport_id;
    } elseif ($roleName === 'trener') {
        $user->sport_id = $request->sport_id;
        $user->points = null;
    } else {
        // Admin
        $user->points = null;
        $user->sport_id = null;
    }

    $user->approved = true;
    $user->save();

    return redirect()->route('admin.users.index')->with('success', 'Użytkownik zatwierdzony.');
}


    public function reject($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Użytkownik odrzucony.');
    }

    public function showEventRegistration()
    {
        $events = Event::where('date', '>', now())->get();
        return view('admin.event_registration', compact('events'));
    }
public function showChangePasswordForm()
{
    return view('admin.change_password');
}
public function changePassword(Request $request)
{
    $admin = auth()->user();

    $request->validate([
        'current_password' => ['required'],
        'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    if (!Hash::check($request->current_password, $admin->password)) {
        return back()->withErrors(['current_password' => 'Obecne hasło jest nieprawidłowe.']);
    }

    $admin->password = Hash::make($request->new_password);
    $admin->save();

    return redirect()->route('admin.index')->with('success', 'Hasło zostało zmienione pomyślnie.');
}
// W AdminController
public function edit($user_id)
{
    $user = User::findOrFail($user_id);
    $roles = Role::all();
    $sports = Sport::where('is_active', true)->get();
    return view('users.edit', compact('user', 'roles', 'sports'));
}

public function update(Request $request, $user_id)
{
    $user = User::findOrFail($user_id);

    $request->validate([
        'name' => 'required|string|max:100',
        'surname' => 'required|string|max:100',
        'email' => 'required|email|max:150|unique:users,email,' . $user->user_id . ',user_id',
        'role_id' => 'required|exists:roles,role_id',
        'sport_id' => 'nullable|exists:sports,sport_id',
        'phone' => 'required|regex:/^\d{9,11}$/',
        'approved' => 'required|boolean',
    ]);
if ($request->sport_id) {
        $sport = Sport::find($request->sport_id);
        if (!$sport || !$sport->is_active) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['sport_id' => 'Wybrana dyscyplina jest zarchiwizowana i nie może zostać przypisana.']);
        }
    }
    $user->update($request->only('name','surname','email','role_id','sport_id','phone','approved'));

    return redirect()->route('admin.index')->with('success', 'Użytkownik został zaktualizowany.');
}

    }
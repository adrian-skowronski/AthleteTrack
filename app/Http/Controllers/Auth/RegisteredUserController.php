<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    
    public function create(): View
    {
        return view('auth.register');
    }

   
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'regex:/^[A-ZĄĆĘŁŃÓŚŻŹ][a-ząćęłńóśżź]{1,}(?:\s[A-ZĄĆĘŁŃÓŚŻŹ][a-ząćęłńóśżź]{1,})?$/u', 'max:80'],
'surname' => [
    'required',
    'string',
    'regex:/^[A-Za-zĄĆĘŁŃÓŚŻŹąćęłńóśżź][a-ząćęłńóśżź]{1,}$/u',
    'max:80'
],
'phone' => [
    'required',
    'regex:/^[0-9]{9}$/'
],
        'birthdate' => ['required', 'date', 'after_or_equal:1920-01-01'],
        'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'surname' => $request->surname,
        'phone' => $request->phone,
        'birthdate' => $request->birthdate,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'approved' => false, 
    ]);

    event(new Registered($user));

    return redirect()->route('auth.notice');}

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class AuthenticationController extends Controller
{
    function showLoginForm()
    {
        return view('auth.login');
    }

    function showRegisterForm()
    {
        return view('auth.register');
    }

    function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create($validated);

        auth()->login($user);

        return response()->redirectTo(route('dashboard'))
            ->with('success', 'Registration successful');
    }

    function login(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if (auth()->attempt($validated)) {
            return response()->redirectTo(route('dashboard'))
                ->with('success', 'Login successful');
        }
    }

    function logout(Request $request)
    {
        auth()->logout();
        return response()->redirectTo(route('login'))
            ->with('success', 'Logout successful');
    }
}

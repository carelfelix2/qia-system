<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'requested_role' => $validated['requested_role'],
            'status' => 'pending',
            'registration_date' => now(),
        ]);

        // Do not assign role yet - wait for admin approval

        // Notify all admin users about new registration
        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewUserRegistrationNotification($user));
        }

        event(new Registered($user));

        // Auto-login the user
        Auth::login($user);

        // Regenerate session to prevent session fixation
        $request->session()->regenerate();

        // Redirect to dashboard based on role
        return redirect(route('dashboard', absolute: false));
    }
}

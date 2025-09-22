<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Log successful registration
            LogService::auth('register', $user->email, [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'registered_at' => $user->created_at->toISOString(),
                'auto_login' => true,
            ]);

            event(new Registered($user));

            Auth::login($user);

            return to_route('dashboard');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log failed registration attempt
            LogService::warning(LogService::CHANNEL_AUTH, 'Intento de registro fallido - validación', [
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'validation_errors' => $e->errors(),
                'attempted_at' => now()->toISOString(),
            ]);
            
            throw $e;
        } catch (\Exception $e) {
            // Log unexpected registration errors
            LogService::error('Error inesperado durante el registro de usuario', $e, [
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'attempted_at' => now()->toISOString(),
            ]);
            
            throw $e;
        }
    }
}

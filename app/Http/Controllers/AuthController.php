<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /**
     * Procesa la autenticación con protecciones de seguridad:
     * - Rate limiting (anti fuerza bruta)
     * - Regeneración de sesión (anti session fixation)
     * - Registro de auditoría (OWASP A09)
     */
    public function login(Request $request)
    {
        $throttleKey = 'login:' . $request->ip() . '|' . strtolower($request->input('email', ''));

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            Log::warning('Segurtasun abisua: Saiakera gehiegi saioa hastean (Brute force detected)', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'lockout_seconds' => $seconds
            ]);
            return back()->with('error', "Saiakera gehiegi egin dituzu. Mesedez, itxaron {$seconds} segundo berriro saiatu aurretik.");
        }

        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // OWASP A07: Regenerar ID de sesión para mitigar Session Fixation
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey);

            $user = Auth::user();
            Log::info("Saioa hasi da: {$user->email} (Rol: {$user->role})", [
                'ip' => $request->ip()
            ]);

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', "Ongi etorri berriro, Administratzailea {$user->name}!");
            }

            return redirect()->route('home')->with('success', "Ongi etorri berriro, {$user->name}!");
        }

        RateLimiter::hit($throttleKey, 60);

        Log::warning('Hutsegitea saioa hastean', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        // Mensaje genérico para prevenir enumeración de usuarios (OWASP A07)
        return back()->withErrors([
            'email' => 'Kredentzial okerrak eman dira.',
        ])->onlyInput('email');
    }

    /**
     * Muestra el formulario de registro de nuevos usuarios
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Procesa el registro de nuevos usuarios:
     * - Forzado de rol 'ikasle' (previene elevación de privilegios / mass assignment)
     * - Hashing seguro de contraseña con Bcrypt
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min' => 'Pasahitzak gutxienez 8 karaktere izan behar ditu.',
            'email.unique' => 'Email hau dagoeneko erregistratuta dago sisteman.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 'ikasle', // Estudiante por defecto en registro público
        ]);

        Log::info("Erabiltzaile berria erregistratu da: {$user->email}", [
            'ip' => $request->ip()
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Erregistroa arrakastaz burutu da! Ongi etorri plataformara.');
    }

    /**
     * Cierre de sesión seguro:
     * - Invalida la sesión actual
     * - Regenera el token CSRF
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Log::info("Saioa itxi da: " . Auth::user()->email);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Saioa ondo itxi da.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Erabiltzaileen autentifikaziorako kontrolatzailea (Login, Erregistroa, Logout).
 * Segurtasun neurri aurreratuak txertatzen ditu: Rate limiting, Session fixation prebentzioa, eta auditoretza erregistroa.
 */
class AuthController extends Controller
{
    /**
     * Saioa hasteko formularioa erakusten du (erabiltzailea jada konektatuta ez badago).
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /**
     * Erabiltzailearen autentifikazioa prozesatzen du segurtasun neurriekin:
     * - Tasa mugatzea (Rate Limiting) indar gordinaren kontra.
     * - Saioaren ID berritzea (Session Fixation arriskua ezabatzeko).
     * - Erregistro segurua (Log A09).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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
            // OWASP A07: Saioaren IDa birsortu Session Fixation erasoak saihesteko
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

        // Errore mezu orokorra erabiltzaileen enumerazioa prebenitzeko (OWASP A07)
        return back()->withErrors([
            'email' => 'Kredentzial okerrak eman dira.',
        ])->onlyInput('email');
    }

    /**
     * Erabiltzaile berria erregistratzeko formularioa erakusten du.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Erabiltzaile berria sisteman erregistratzen du:
     * - 'ikasle' rola derrigortzen du (baimenen goratzea saihesteko).
     * - Pasahitza Bcrypt bidez zifratzen du.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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
            'role' => 'ikasle', // Ikasle rola lehenetsi erregistro publikoan
        ]);

        Log::info("Erabiltzaile berria erregistratu da: {$user->email}", [
            'ip' => $request->ip()
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Erregistroa arrakastaz burutu da! Ongi etorri plataformara.');
    }

    /**
     * Saioa segurtasunez ixten du:
     * - Uneko saioa baliogabetzen du (Session Invalidation).
     * - CSRF tokena berritzen du.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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

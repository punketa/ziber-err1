<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Erabiltzaileen kudeaketarako kontrolatzailea (CRUD osoa).
 * Administratzaileari erabiltzaileak sortu, ikusi, editatu eta ezabatzeko aukera ematen dio.
 */
class UsuarioController extends Controller
{
    /**
     * Erabiltzaile guztien zerrenda bistaratzen du matrikula kopuruekin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $usuarios = User::withCount('matriculas')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Erabiltzaile berria eskuz sortzeko formularioa erakusten du.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Erabiltzaile berria balidatu, pasahitza Bcrypt bidez babestu eta datu-basean gordetzen du.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,ikasle',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        Log::info("Admin-ak erabiltzaile berria sortu du: {$user->email} (Rol: {$user->role}) by Admin: " . Auth::user()->email);

        return redirect()->route('admin.usuarios.index')->with('success', 'Erabiltzailea arrakastaz sortu da!');
    }

    /**
     * Erabiltzailea aldatzeko formularioa bistaratzen du.
     *
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\View\View
     */
    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Aldatutako erabiltzailearen datuak balidatu eta eguneratzen ditu.
     * Segurtasuna: Administratzaile batek ezin dio bere buruari rola kendu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $usuario->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,ikasle',
        ]);

        // Ciberseguridad: evitar que el único administrador se degrade a sí mismo
        if ($usuario->id === Auth::id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'Ezin diozu zure buruari administratzaile rola kendu.');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $usuario->update($validated);

        Log::info("Admin-ak erabiltzailea eguneratu du: {$usuario->email}");

        return redirect()->route('admin.usuarios.index')->with('success', 'Erabiltzailea arrakastaz eguneratu da!');
    }

    /**
     * Erabiltzaile bat datu-basetik ezabatzen du.
     * Segurtasuna: Erabiltzaile batek ezin du bere burua ezabatu.
     *
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $usuario)
    {
        // Ciberseguridad: prevenir que un admin se elimine a sí mismo
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'Ezin duzu zure erabiltzailea zeuk ezabatu.');
        }

        $email = $usuario->email;
        $usuario->delete();

        Log::info("Admin-ak erabiltzailea ezabatu du: {$email} by Admin: " . Auth::user()->email);

        return redirect()->route('admin.usuarios.index')->with('success', 'Erabiltzailea ezabatu da!');
    }
}

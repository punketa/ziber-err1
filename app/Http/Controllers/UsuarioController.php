<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::withCount('matriculas')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

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

    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

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

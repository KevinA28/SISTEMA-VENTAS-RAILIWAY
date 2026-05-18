<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invitacion;
use App\Models\UsuarioAdmin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(string $token): View|RedirectResponse
    {
        $invitacion = Invitacion::where('token', $token)->first();

        if (! $invitacion || ! $invitacion->estaVigente()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'La invitación no es válida o ha expirado.']);
        }

        return view('auth.register', compact('invitacion', 'token'));
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $invitacion = Invitacion::where('token', $token)->first();

        if (! $invitacion || ! $invitacion->estaVigente()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'La invitación no es válida o ha expirado.']);
        }

        $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $usuario = UsuarioAdmin::create([
            'nombre'     => $request->nombre,
            'apellido'   => $request->apellido,
            'email'      => $invitacion->email,
            'password'   => Hash::make($request->password),
            'rol'        => $invitacion->rol,
            'activo'     => true,
            'invited_by' => $invitacion->invitado_por,
        ]);

        $invitacion->update(['usado_at' => now()]);

        Auth::guard('web')->login($usuario);

        return redirect()->route('dashboard');
    }
}
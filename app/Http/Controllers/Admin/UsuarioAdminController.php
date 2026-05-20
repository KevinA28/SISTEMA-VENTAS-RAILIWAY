<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitacion;
use App\Models\UsuarioAdmin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsuarioAdminController extends Controller
{
    public function index()
    {
        $usuarios     = UsuarioAdmin::orderBy('nombre')->get();
        $invitaciones = Invitacion::whereNull('usado_at')
                                  ->where('expires_at', '>', now())
                                  ->with('invitadoPor')
                                  ->orderByDesc('created_at')
                                  ->get();

        return view('admin.usuarios.index', compact('usuarios', 'invitaciones'));
    }

    public function invitar(Request $request): RedirectResponse
    {
        $rolesPermitidos = auth()->user()->rol === 'superadmin'
            ? 'superadmin,administrador,ventas'
            : 'ventas';

        $request->validate([
            'email' => [
                'required', 'email',
                'unique:usuarios_admin,email',
                'unique:invitaciones,email',
            ],
            'rol' => ['required', 'in:' . $rolesPermitidos],
        ]);

        // Eliminar invitaciones previas expiradas para ese email
        Invitacion::where('email', $request->email)->delete();

        $token = Str::random(64);

        $invitacion = Invitacion::create([
            'email'        => $request->email,
            'rol'          => $request->rol,
            'token'        => $token,
            'invitado_por' => auth()->id(),
            'expires_at'   => now()->addDays(3),
        ]);

        $link = route('register', ['token' => $token]);

        Mail::send('emails.invitacion', [
            'invitacion'  => $invitacion,
            'link'        => $link,
            'invitadoPor' => auth()->user()->nombre_completo,
        ], function ($m) use ($request) {
            $m->to($request->email)
              ->subject('Invitación para acceder al sistema de reservas');
        });

        return back()->with('success', "Invitación enviada a {$request->email}. Válida por 3 días.");
    }

    public function toggleActivo(UsuarioAdmin $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes desactivar tu propia cuenta.']);
        }

        if ($usuario->rol === 'superadmin' && auth()->user()->rol !== 'superadmin') {
            abort(403);
        }

        $usuario->update(['activo' => ! $usuario->activo]);

        $estado = $usuario->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario {$usuario->nombre_completo} {$estado}.");
    }

    public function destroy(UsuarioAdmin $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta.']);
        }

        if ($usuario->rol === 'superadmin' && auth()->user()->rol !== 'superadmin') {
            abort(403);
        }

        $usuario->delete();
        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    public function cancelarInvitacion(Invitacion $invitacion): RedirectResponse
    {
        $invitacion->delete();
        return back()->with('success', 'Invitación cancelada.');
    }
    public function cambiarRol(UsuarioAdmin $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes cambiar tu propio rol.']);
        }

        if ($usuario->rol === 'superadmin') {
            return back()->withErrors(['error' => 'El rol de superadmin no se puede cambiar desde aquí.']);
        }

        $nuevoRol = $usuario->rol === 'administrador' ? 'ventas' : 'administrador';
        $usuario->update(['rol' => $nuevoRol]);

        return back()->with('success',
            "Rol de {$usuario->nombre_completo} cambiado a " . ucfirst($nuevoRol) . "."
        );
    }
}
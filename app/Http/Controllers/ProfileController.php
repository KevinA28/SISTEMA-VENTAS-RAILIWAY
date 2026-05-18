<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Subir foto de perfil
        if ($request->hasFile('foto_perfil')) {
            // Eliminar foto anterior si existe
            if ($user->foto_perfil) {
                Storage::disk('public')->delete($user->foto_perfil);
            }
            $user->foto_perfil = $request->file('foto_perfil')
                ->store('fotos_perfil', 'public');
        }

        // Eliminar foto si el usuario lo pidió
        if ($request->boolean('eliminar_foto') && $user->foto_perfil) {
            Storage::disk('public')->delete($user->foto_perfil);
            $user->foto_perfil = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Eliminar foto si existe
        if ($user->foto_perfil) {
            Storage::disk('public')->delete($user->foto_perfil);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
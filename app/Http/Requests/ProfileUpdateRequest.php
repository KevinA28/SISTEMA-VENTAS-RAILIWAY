<?php

namespace App\Http\Requests;

use App\Models\UsuarioAdmin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre'        => ['required', 'string', 'max:100'],
            'apellido'      => ['required', 'string', 'max:100'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255',
                                Rule::unique(UsuarioAdmin::class)->ignore($this->user()->id)],
            'foto_perfil'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'eliminar_foto' => ['nullable', 'boolean'],
        ];
    }
}
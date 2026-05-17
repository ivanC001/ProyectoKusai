<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVerificacionUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->esAdmin();
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dni_legible' => ['nullable', 'boolean'],
            'datos_coinciden' => ['nullable', 'boolean'],
            'contacto_validado' => ['nullable', 'boolean'],
            'estado' => ['required', Rule::in(['pendiente', 'aprobado', 'rechazado'])],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ];
    }
}


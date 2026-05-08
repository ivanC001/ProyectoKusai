<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $dniRequired = $user !== null
            && $user->tipo_persona === 'natural'
            && trim((string) $user->dni) === '';

        return [
            'name' => ['required', 'string', 'max:120'],
            'apellidos' => ['nullable', 'string', 'max:120'],
            'dni' => [
                Rule::requiredIf($dniRequired),
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'dni')->ignore($user?->id),
            ],
            'telefono' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'empresa' => ['nullable', 'string', 'max:180'],
            'nombre_comercial' => ['nullable', 'string', 'max:180'],
            'foto_perfil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}

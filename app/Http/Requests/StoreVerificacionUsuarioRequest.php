<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVerificacionUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dni' => ['required', 'string', 'max:20'],
            'dni_frontal' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'dni_reverso' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'confirmo_datos' => ['required', 'accepted'],
        ];
    }
}


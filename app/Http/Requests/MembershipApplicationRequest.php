<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MembershipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Correspond aux champs REQUIRED dans resources/views/membership/apply.blade.php
        return [
            // Champs actuels du formulaire (nom complet, email, phone, message)
            // On les valide en les gardant cohérents avec la table 'members'.
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['nullable', 'string'],

            // Ces champs peuvent exister dans d’anciennes versions, on les autorise mais sans les rendre obligatoires.
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'address' => ['sometimes', 'required', 'string'],
            'birth_date' => ['sometimes', 'required', 'date'],
        ];
    }
}


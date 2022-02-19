<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePatientGuardianRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'relation' => 'required|string',
            'name'     => 'required|string',
            'email'    => 'string|email',
            'phone'    => 'phone:AUTO',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Rules\Phone;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientGuardianRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'relation' => 'sometimes|required|string',
            'name'     => 'sometimes|required|string',
            'email'    => 'string|email',
            'phone'    => ['sometimes', 'required', 'string', new Phone()],
        ];
    }
}

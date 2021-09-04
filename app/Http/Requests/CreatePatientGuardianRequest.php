<?php

namespace App\Http\Requests;

use App\Rules\Phone;
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
            'phone'    => ['string', new Phone()]
        ];
    }
}

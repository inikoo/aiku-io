<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name'     => 'sometimes|required',
            'country'  => 'sometimes|required',
            'currency' => 'sometimes|required',
            'language' => 'sometimes|required',
            'timezone' => 'sometimes|required',
        ];
    }
}

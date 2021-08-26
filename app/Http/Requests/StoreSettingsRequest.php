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
            'country_id'  => 'sometimes|required|exists:App\Models\Assets\Country,id',
            'currency_id' => 'sometimes|required|exists:App\Models\Assets\Currency,id',
            'language_id' => 'sometimes|required|exists:App\Models\Assets\Language,id',
            'timezone_id' => 'sometimes|required|exists:App\Models\Assets\Timezone,id',
        ];
    }
}

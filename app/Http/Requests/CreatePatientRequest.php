<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 04:21:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Requests;

use App\Models\Assets\Country;
use App\Rules\Phone;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

/**
 * @property mixed $country_id
 * @property mixed $date_of_birth
 */
class CreatePatientRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->date_of_birth) {
            $this->merge([
                             'date_of_birth' => Carbon::parse($this->date_of_birth)->format('Y-m-d')
                         ]);
        }
    }


    public function rules(): array
    {
        $rules = [
            'type'                         => [
                'required',
                Rule::in(['dependant', 'adult']),
            ],
            'name'                         => 'required|string',
            'date_of_birth'                => 'required|date|before_or_equal:today',
            'gender'                       => [
                'required',
                Rule::in(['male', 'female']),
            ],
            'identity_document_type'       => 'required_with:identity_document_number',
            'identity_document_number'     => 'required_with:identity_document_type',
            'other_identity_document_type' => 'required_if:identity_document_type,Other',
            'relation'                     => 'required|string',
            'email'                        => 'string|email',
            'phone'                        => ['string', new Phone()],
            'guardian_name'                => 'required_if:type,dependant',


        ];

        $rules['country_id'] = 'required|exists:App\Models\Assets\Country,id';
        $country             = Country::find($this->country_id);
        foreach (Arr::get($country->data, 'fields', []) as $field => $fieldData) {
            $rules[$field] = (isset($fieldData['required']) ? 'required' : 'nullable').'|string';
        }

        return $rules;
    }
}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 03 Sep 2021 04:16:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Requests;

use App\Models\Assets\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

/**
 * @property mixed $country_id
 */
class UpdatePatientGuardianAddressRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $rules               = [];
        $rules['country_id'] = 'required|exists:App\Models\Assets\Country,id';
        $country             = Country::find($this->country_id);
        foreach (Arr::get($country->data, 'fields', []) as $field => $fieldData) {
            $rules[$field] = (isset($fieldData['required']) ? 'required' : 'nullable').'|string';
        }

        return $rules;
    }
}

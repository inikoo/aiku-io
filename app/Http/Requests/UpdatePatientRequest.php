<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 06:56:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Requests;

use App\Rules\Phone;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property mixed date_of_birth
 */
class UpdatePatientRequest extends FormRequest
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
        return [
            'name'                         => 'sometimes|required|string',
            'date_of_birth'                => 'sometimes|required|date|before_or_equal:today',
            'gender'                       => [
                'sometimes|required',
                Rule::in(['male', 'female']),
            ],
            'email'                        => 'sometimes|email',
            'phone'                        => ['sometimes', 'string', new Phone()],
            'identity_document_type'       => 'sometimes|required_with:identity_document_number',
            'identity_document_number'     => 'sometimes|required_with:identity_document_type',
            'other_identity_document_type' => 'sometimes|required_if:identity_document_type,Other'
        ];
    }
}

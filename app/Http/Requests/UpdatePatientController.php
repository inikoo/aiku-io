<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 06:56:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed date_of_birth
 */
class UpdatePatientController extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {

        if($this->date_of_birth){
            $this->merge([
                             'date_of_birth' => Carbon::parse($this->date_of_birth)->format('Y-m-d')
                         ]);
        }


    }

    public function rules(): array
    {
        return [
            'name'          => 'sometimes|required',
            'date_of_birth' => 'sometimes|required|date',
            'gender'        => 'sometimes|required',
        ];
    }
}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 22 Sep 2021 00:50:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user()->tokenCan('root') || $this->user()->tokenCan('employee:store');
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }
}

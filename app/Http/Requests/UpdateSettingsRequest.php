<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 25 Aug 2021 21:00:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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

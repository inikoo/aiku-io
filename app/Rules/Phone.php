<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 05 Sep 2021 00:14:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Twilio\Rest\Client;

class Phone implements Rule
{

    public function __construct()
    {
        //
    }


    public function passes($attribute, $value): bool
    {


        try {
            $twilio = new Client(config('app.twilio_sid'), config('app.twilio_token'));
            $twilio->lookups->v1->phoneNumbers($value)->fetch();

            return true;
        } catch (Exception) {
            return false;
        }


    }


    public function message(): string
    {
        return trans('validation.phone');
    }
}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Mar 2022 23:12:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;


use function config;

trait WithInvalidAddress
{

    public function fixAddress($prefix, $data)
    {
        if ($data->{"$prefix Address Country 2 Alpha Code"} == 'XX' and $data->{"$prefix Address Postal Code"} == '35550') {
            $data->{"$prefix Address Country 2 Alpha Code"} = 'ES';
        } elseif ($data->{"$prefix Address Country 2 Alpha Code"} == 'XX' and
            $data->{"$prefix Address Postal Code"} == '' and
            $data->{"$prefix Address Postal Town"} == '' and
            $data->{"$prefix Address Postal Address Line 1"} == '') {
            $data->{"$prefix Address Country 2 Alpha Code"} = config('app.country');
        }


        return $data;
    }
}

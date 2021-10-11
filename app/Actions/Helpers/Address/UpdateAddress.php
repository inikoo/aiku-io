<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 27 Sep 2021 18:41:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Address;

use App\Models\Helpers\Address;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAddress
{
    use AsAction;

    public function handle(Address $address,array $data): Address
    {
        $address->update($data);
        return $address;
    }
}
<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 24 Feb 2022 19:53:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


namespace App\Models\Auth;

use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;


class LandlordPersonalAccessToken extends PersonalAccessToken
{
    use UsesLandlordConnection;

    protected $table='personal_access_tokens';
}

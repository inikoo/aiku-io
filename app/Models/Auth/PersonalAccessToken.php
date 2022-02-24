<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 24 Feb 2022 19:53:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


namespace App\Models\Auth;

use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;


/**
 * @mixin IdeHelperPersonalAccessToken
 */
class PersonalAccessToken extends \Laravel\Sanctum\PersonalAccessToken
{
    use UsesLandlordConnection;
}

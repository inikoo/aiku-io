<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 31 Mar 2022 16:16:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Auth;

use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


class TenantPersonalAccessToken extends PersonalAccessToken
{
    use UsesTenantConnection;
    protected $table='personal_access_tokens';

}

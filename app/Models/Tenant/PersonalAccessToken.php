<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 22 Sep 2021 21:35:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


namespace App\Models\Tenant;

use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperPersonalAccessToken
 */
class PersonalAccessToken extends \Laravel\Sanctum\PersonalAccessToken
{
    use UsesTenantConnection;
}

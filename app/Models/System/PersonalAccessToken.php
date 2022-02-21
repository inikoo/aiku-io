<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 22 Feb 2022 01:41:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


namespace App\Models\System;

use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperPersonalAccessToken
 */
class PersonalAccessToken extends \Laravel\Sanctum\PersonalAccessToken
{
    use UsesTenantConnection;
}

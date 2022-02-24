<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 24 Feb 2022 03:58:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Permission\Models\Permission as SpatiePermission;


/**
 * @mixin IdeHelperPermission
 */
class Permission extends SpatiePermission
{
    use UsesLandlordConnection;
}

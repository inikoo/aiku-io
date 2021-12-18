<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Dec 2021 14:21:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperSupervisor
 */
class Supervisor extends Pivot
{

    public $incrementing = true;


}



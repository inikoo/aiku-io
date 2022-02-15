<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 15 Feb 2022 01:09:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;



/**
 * @mixin IdeHelperDepartmentStats
 */
class DepartmentStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'department_stats';

    protected $guarded = [];


    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }


}

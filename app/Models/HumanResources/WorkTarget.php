<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 01 Jan 2022 23:19:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperWorkTarget
 */
class WorkTarget extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $guarded = [];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function timeTrackings(): HasMany
    {
        return $this->hasMany(TimeTracking::class);
    }
}

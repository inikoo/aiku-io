<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 04 Jan 2022 05:28:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperClockingMachine
 */
class ClockingMachine extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $casts = [
        'data'          => 'array',

    ];

    protected $attributes = [
        'data'          => '{}',

    ];

    protected $guarded = [];

    public function workPlace(): BelongsTo
    {
        return $this->belongsTo(Workplace::class);
    }

    public function createdClockings(): MorphToMany
    {
        return $this->morphedByMany(Clocking::class, 'generator');
    }

    public function deletedClockings(): MorphToMany
    {
        return $this->morphedByMany(Clocking::class, 'deleter');
    }


}

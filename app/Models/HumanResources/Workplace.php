<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 02 Jan 2022 15:27:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\HumanResources;

use App\Models\Account\Tenant;
use App\Models\Traits\HasAddress;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperWorkplace
 */
class Workplace extends Model implements Auditable
{
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    use HasAddress;
    use HasSlug;

    protected $guarded = [];

    public function getSlugSourceAttribute(): string
    {
        /** @var Employee|Guest|Tenant $owner */
        $owner=$this->owner;

        return $this->type.'_'.$owner->nickname;
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function employees(): MorphToMany
    {
        return $this->morphedByMany(Employee::class, 'workplace_user')->withTimestamps();
    }

    public function guests(): MorphToMany
    {
        return $this->morphedByMany(Guest::class, 'workplace_user')->withTimestamps();
    }

    public function clockingMachines(): HasMany
    {
        return $this->hasMany(ClockingMachine::class);
    }

}

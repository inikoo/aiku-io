<?php

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperTimeTracking
 */
class TimeTracking extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $guarded = [];

    public function clockings(): HasMany
    {
        return $this->hasMany(Clocking::class);
    }

    public function workplace(): HasOne
    {
        return $this->hasOne(Workplace::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function startClocking(): HasOne
    {
        return $this->hasOne(Clocking::class,'start_clocking_id');
    }

    public function endClocking(): HasOne
    {
        return $this->hasOne(Clocking::class,'end_clocking_id');
    }


}

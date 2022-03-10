<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 05 Jan 2022 15:53:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperClocking
 */
class Clocking extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function (Clocking $clocking) {

                //todo Remove this if after migration, (deleted_at can have a value when migrating)
                if($clocking->deleted_at==null){
                    if (!$clocking->timeTracking->start_clocking_id ) {
                        $clocking->timeTracking->start_clocking_id = $clocking->id;
                        $clocking->timeTracking->starts_at = $clocking->clocked_at;
                        $clocking->timeTracking->status = 'open';
                    } else {
                        $clocking->timeTracking->end_clocking_id = $clocking->id;
                        $clocking->timeTracking->ends_at = $clocking->clocked_at;
                        $clocking->timeTracking->status = 'closed';
                    }
                    $clocking->timeTracking->save();
                }


            }
        );
    }


    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public function generator(): MorphTo
    {
        return $this->morphTo();
    }

    public function deleter(): MorphTo
    {
        return $this->morphTo();
    }

    public function timeTracking(): BelongsTo
    {
        return $this->belongsTo(TimeTracking::class);
    }
}

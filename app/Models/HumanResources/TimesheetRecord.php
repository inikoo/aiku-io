<?php

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperTimesheetRecord
 */
class TimesheetRecord extends Model
{
    use HasFactory;
    use UsesTenantConnection;


    public function employee(): BelongsTo
    {
        return $this->belongsTo(Timesheet::class);
    }
}

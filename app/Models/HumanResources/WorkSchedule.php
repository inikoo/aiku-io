<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 03 Jan 2022 00:37:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\HumanResources;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperWorkSchedule
 */
class WorkSchedule extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $casts = [
        'breaks' => 'array',
        'starts_at' => 'datetime:H:i:s',
        'ends_at' => 'datetime:H:i:s'
    ];

    protected $attributes = [
        'breaks' => '[]',
    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(
            function (WorkSchedule $workSchedule) {
                $workSchedule->setLengths();
                $workSchedule->checksum = $workSchedule->getChecksum();
            }
        );

    }


    public function getChecksum(): string
    {
        return md5(
            json_encode(
                [
                    's' => $this->starts_at,
                    'e' => $this->ends_at,
                    'b' => $this->breaks
                ]
            )
        );
    }

    public function workTargets(): HasMany
    {
        return $this->hasMany(WorkTarget::class);
    }

    public function setLengths()
    {
        $length = 0;
        if ($this->ends_at and $this->starts_at) {
            /** @var \Carbon\Carbon $start */
            $start=$this->ends_at;
            $length = $start->diffInMinutes($this->starts_at);
        }
        $breakLength = 0;
        foreach ($this->breaks as $break) {
            $start=Carbon::parse($break[0]);
            $end=Carbon::parse($break[1]);
            $breakLength+=$end->diffInMinutes($start);
        }

        $this->gross_length = $length;
        $this->length       = $length - $breakLength;
    }

}

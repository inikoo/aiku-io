<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 25 Aug 2021 23:26:53 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;


/**
 * @mixin IdeHelperTimezone
 */
class Timezone extends Model
{
    use UsesLandlordConnection;

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function formatOffset(): string
    {
        $hours     = $this->offset / 3600;
        $remainder = $this->offset % 3600;
        $sign      = $hours > 0 ? '+' : '-';
        $hour      = (int)abs($hours);
        $minutes   = (int)abs($remainder / 60);

        if ($hour == 0 and $minutes == 0) {
            $sign = ' ';
        }

        return 'GMT'.$sign.str_pad($hour, 2, '0', STR_PAD_LEFT).':'.str_pad($minutes, 2, '0');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }
}

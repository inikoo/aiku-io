<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 07 Nov 2021 17:19:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models;

use App\Models\Inventory\Location;
use App\Models\Inventory\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LocationStock extends Pivot
{
    use HasFactory;


    protected $casts = [
        'data'     => 'array',
        'settings'     => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
        'settings' => '{}',
    ];


    protected $guarded = [];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }



}

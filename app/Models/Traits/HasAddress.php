<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 02 Jan 2022 15:30:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Traits;


use App\Models\Helpers\Address;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAddress
{

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /** @noinspection PhpUnused */
    public function getFormattedAddressAttribute(): string
    {
        if ($this->address) {
            return $this->address->formatted_address;
        } else {
            return '';
        }
    }

}



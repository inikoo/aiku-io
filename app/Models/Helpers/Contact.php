<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 28 Aug 2021 00:35:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Helpers;

use App\Models\Health\Patient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperContact
 */
class Contact extends Model
{
    use UsesTenantConnection;
    use HasFactory;

    protected $appends = ['formatted_address'];


    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'data'     => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class,'owner');
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class)->withPivot('relation')->withTimestamps();
    }

    /** @noinspection PhpUnused */
    public function getFormattedAddressAttribute(): string
    {
        return $this->address->formatted_address;
    }

}

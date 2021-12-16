<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 16:04:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Delivery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperPicking
 */
class Picking extends Model
{
    use HasFactory;
    use UsesTenantConnection;


    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function deliveryNote(): BelongsTo
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    public function deliveryNoteItems(): BelongsToMany
    {
        return $this->belongsToMany(deliveryNoteItem::class)->withTimestamps();
    }

    /** @noinspection PhpUnused */
    public function setRequiredAttribute($val)
    {
        $this->attributes['required'] = sprintf('%.3f', $val);
    }

    /** @noinspection PhpUnused */
    public function setPickedAttribute($val)
    {
        $this->attributes['picked'] = is_null($val) ? null:   sprintf('%.3f', $val);
    }

    /** @noinspection PhpUnused */
    public function setWeightAttribute($val)
    {
        $this->attributes['weight'] = is_null($val) ? null:   sprintf('%.3f', $val);
    }

}

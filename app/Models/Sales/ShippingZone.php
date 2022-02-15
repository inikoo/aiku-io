<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 18 Nov 2021 16:18:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Sales;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperShippingZone
 */
class ShippingZone  extends Model implements Auditable
{
    use HasFactory;
    use HasSlug;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;


    protected $casts = [
        'settings' => 'array',
    ];

    protected $attributes = [
        'settings' => '{}',
    ];

    protected $guarded = [];

    public function getSlugSourceAttribute(): string
    {
        return  $this->shippingSchema->slug.'-'.$this->code;
    }

    public function shippingSchema(): BelongsTo
    {
        return $this->belongsTo(ShippingSchema::class);
    }



}

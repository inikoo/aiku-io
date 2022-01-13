<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 15:17:17 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Trade;

use App\Models\Media\Image;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperProduct
 */
class Product extends Model implements Auditable
{
    use HasSlug;
    use \OwenIt\Auditing\Auditable;
    use UsesTenantConnection;
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'data' => 'array',
        'settings' => 'array',
        'status' => 'boolean',
    ];

    protected $attributes = [
        'data' => '{}',
        'settings' => '{}',
    ];

    /** @noinspection PhpUnused */
    public function getSlugSourceAttribute(): string
    {
        /**  @var \App\Models\Trade\Shop|\App\Models\Buying\Supplier $vendor*/
        $vendor=$this->vendor;

        $prefix=match ($this->vendor_type){
            'Shops'=>'shp',
            'Supplier'=>'supplier',
            default=>strtolower($this->vendor_type)
        };


        return $this->code.'-'.$prefix.'-'.$vendor->code;
    }

    protected $guarded = [];

    public function vendor(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'vendor_type', 'vendor_id')->withTrashed();

    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'image_model', 'imageable_type', 'imageable_id');
    }

    public function historicRecords(): HasMany
    {
        return $this->hasMany(HistoricProduct::class)->withTrashed();
    }

    public function setPriceAttribute($val)
    {
        $this->attributes['price'] = sprintf('%.2f', $val);
    }

    public function tradeUnits(): BelongsToMany
    {
        return $this->belongsToMany(TradeUnit::class)->withPivot('quantity')->withTimestamps();
    }

}

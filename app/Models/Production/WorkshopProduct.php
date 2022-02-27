<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 03:06:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Production;

use App\Models\Media\Image;
use App\Models\SalesStats;
use App\Models\Marketing\TradeUnit;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperWorkshopProduct
 */
class WorkshopProduct extends Model implements Auditable
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
    protected $guarded = [];

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class)->withTrashed();
    }

    public function getSlugSourceAttribute(): string
    {
        return $this->code.'-prod-'.$this->workshop->code;
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'image_model', 'imageable_type', 'imageable_id');
    }

    public function historicRecords(): HasMany
    {
        return $this->hasMany(HistoricWorkshopProduct::class)->withTrashed();
    }

    public function tradeUnits(): BelongsToMany
    {
        return $this->belongsToMany(TradeUnit::class)->withPivot('quantity')->withTimestamps();
    }

    public function salesStats(): MorphOne
    {
        return $this->morphOne(SalesStats::class, 'model')->where('scope','sales-tenant-currency');
    }

    public function setCostAttribute($val)
    {
        $this->attributes['cost'] = sprintf('%.4f', $val);
    }


}

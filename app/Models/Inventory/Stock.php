<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 12:36:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Inventory;

use App\Models\Trade\TradeUnit;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Sluggable\SlugOptions;

/**
 * @mixin IdeHelperStock
 */
class Stock extends Model implements Auditable
{
    use HasSlug;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'data' => 'array',
        'settings' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
        'settings' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /** @noinspection PhpUnused */
    public function setQuantityAttribute($val)
    {
        $this->attributes['price'] = sprintf('%.3f', $val);
    }

    public function setValueAttribute($val)
    {
        $this->attributes['price'] = sprintf('%.2f', $val);
    }


    public function tradeUnits(): BelongsToMany
    {
        return $this->belongsToMany(TradeUnit::class)->withPivot('quantity')->withTimestamps();

    }


}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 01:31:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Web;

use App\Models\Trade\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use App\Models\Traits\HasSlug;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @mixin IdeHelperWebsite
 */
class Website extends Model implements Auditable
{
    use HasSlug;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;


    protected $casts = [
        'data'     => 'array',
        'settings' => 'array'
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected $guarded = [];


    /** @noinspection PhpUnused */
    public function getSlugSourceAttribute(): string
    {
        return $this->code.'-'.$this->shop->code;
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

}

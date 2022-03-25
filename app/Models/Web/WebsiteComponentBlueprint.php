<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 00:52:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

/**
 * @mixin IdeHelperWebsiteComponentBlueprint
 */
class WebsiteComponentBlueprint extends Model
{
    use HasFactory;
    use UsesLandlordConnection;
    use SoftDeletes;

    protected $casts = [
        'data'             => 'array',
        'sample_arguments' => 'array',

    ];

    protected $attributes = [
        'data'             => '{}',
        'sample_arguments' => '{}',

    ];

    protected $guarded = [];

    public function websiteComponents(): HasMany
    {
        return $this->hasMany(WebsiteComponent::class);
    }

}

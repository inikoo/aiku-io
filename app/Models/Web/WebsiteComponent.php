<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 26 Mar 2022 00:56:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperWebsiteComponent
 */
class WebsiteComponent extends Model implements Auditable
{
    use UsesTenantConnection;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $casts = [
        'arguments' => 'array',
        'settings'  => 'array',

    ];

    protected $attributes = [
        'arguments' => '{}',
        'settings'  => '{}',

    ];

    protected $guarded = [];


    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function blueprint(): BelongsTo
    {
        return $this->belongsTo(WebsiteComponentBlueprint::class);
    }


}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 15:24:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperHistoricProduct
 */
class HistoricProduct extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use UsesTenantConnection;
    use SoftDeletes;

    protected $casts = [
        'status' => 'boolean',

    ];

    public $timestamps = ["created_at"];
    public const UPDATED_AT = null;

    protected $guarded = [];

    public function setPriceAttribute($val)
    {
        $this->attributes['price'] = sprintf('%.2f', $val);
    }

    public function setCbmAttribute($val)
    {
        $this->attributes['cbm'] = sprintf('%.4f', $val);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}

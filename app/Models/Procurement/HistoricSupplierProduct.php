<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 02:53:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Procurement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperHistoricSupplierProduct
 */
class HistoricSupplierProduct extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use UsesTenantConnection;
    use SoftDeletes;

    public $timestamps = ["created_at"];
    public const UPDATED_AT = null;

    protected $guarded = [];

    public function setCostAttribute($val)
    {
        $this->attributes['cost'] = sprintf('%.2f', $val);
    }

    public function setCbmAttribute($val)
    {
        $this->attributes['cbm'] = sprintf('%.4f', $val);
    }

    public function supplierProduct(): BelongsTo
    {
        return $this->belongsTo(SupplierProduct::class);
    }

}

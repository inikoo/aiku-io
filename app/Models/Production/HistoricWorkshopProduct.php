<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 02:55:57 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperHistoricWorkshopProduct
 */
class HistoricWorkshopProduct extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use UsesTenantConnection;
    use SoftDeletes;

    public $timestamps = ["created_at"];
    public const UPDATED_AT = null;

    protected $casts=[
        'status'=>'boolean'
    ];

    protected $guarded = [];



    public function workshopProduct(): BelongsTo
    {
        return $this->belongsTo(WorkshopProduct::class);
    }

    public function setCostAttribute($val)
    {
        $this->attributes['cost'] = sprintf('%.4f', $val);
    }

}

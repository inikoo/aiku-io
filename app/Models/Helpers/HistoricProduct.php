<?php

namespace App\Models\Helpers;

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

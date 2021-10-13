<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class HistoricProduct extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use UsesTenantConnection;
    use SoftDeletes;

    public $timestamps = ["created_at"];
    public const UPDATED_AT = null;

    protected $guarded = [];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}

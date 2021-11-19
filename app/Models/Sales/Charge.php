<?php

namespace App\Models\Sales;

use App\Models\Trade\Shop;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @mixin IdeHelperCharge
 */
class Charge extends Model implements Auditable
{
    use HasFactory;
    use HasSlug;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;


    protected $casts = [
        'settings' => 'array',
        'status'   => 'boolean',
    ];

    protected $attributes = [
        'settings' => '{}',
    ];

    protected $guarded = [];

    /** @noinspection PhpUnused */
    public function getSlugSourceAttribute(): string
    {
        return $this->shop->code.'_'.$this->type;
    }


    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

}

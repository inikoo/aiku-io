<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 16:47:17 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Delivery;

use App\Models\CRM\Customer;
use App\Models\Sales\Order;
use App\Models\Trade\Product;
use App\Models\Trade\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperDeliveryNoteItem
 */
class DeliveryNoteItem extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    use SoftDeletes;

    protected $table = 'delivery_note_items';

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function pickings(): BelongsToMany
    {
        return $this->belongsToMany(Picking::class)->withTimestamps();
    }

    /** @noinspection PhpUnused */
    public function setQuantityAttribute($val)
    {
        $this->attributes['quantity'] = sprintf('%.3f', $val);
    }

}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 01 Dec 2021 15:32:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Accounting;

use App\Models\Sales\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperInvoiceTransaction
 */
class InvoiceTransaction extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    use SoftDeletes;

    protected $table = 'invoice_transactions';

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /** @noinspection PhpUnused */
    public function setQuantityAttribute($val)
    {
        $this->attributes['quantity'] = sprintf('%.3f', $val);
    }

}

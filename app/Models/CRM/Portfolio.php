<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 15 Mar 2022 18:03:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\CRM;

use App\Models\Marketing\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;


/**
 * @mixin IdeHelperPortfolio
 */
class Portfolio extends Pivot
{
    use HasFactory;

    protected $table = 'portfolio';

    public $incrementing = true;
    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
        'status'   => 'boolean'

    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',

    ];


    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}

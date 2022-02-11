<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Feb 2022 21:51:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperSalesStats
 */
class SalesStats extends Model
{
    use HasFactory;

    protected $table = 'sales_stats';
    protected $guarded = [];


    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 31 Jan 2022 23:16:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Traits;


use Illuminate\Support\Facades\DB;

trait HasDBDriverAwareQueries
{
    public function likeOperator(): string
    {
        if(DB::connection()->getDriverName()=='pgsql'){
            return 'ILIKE';
        }
        return 'LIKE';

    }

}



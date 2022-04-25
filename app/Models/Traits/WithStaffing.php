<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Apr 2022 15:12:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Traits;


use App\Models\Staffing\Recruiter;
use Illuminate\Database\Eloquent\Relations\MorphOne;


trait WithStaffing
{
    public function recruiter(): MorphOne
    {
        return $this->morphOne(Recruiter::class, 'recruiterable');
    }



}



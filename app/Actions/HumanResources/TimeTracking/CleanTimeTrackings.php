<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 10 Mar 2022 05:43:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\TimeTracking;


use App\Models\HumanResources\TimeTracking;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class CleanTimeTrackings
{
    use AsAction;


    public function handle()
    {

        TimeTracking::where('status','open')->where('starts_at', '<', Carbon::now()->subHours(16))->update(['status'=>'error']);

    }


}

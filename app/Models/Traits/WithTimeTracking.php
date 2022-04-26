<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Apr 2022 15:14:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Traits;


use App\Models\HumanResources\Clocking;
use App\Models\HumanResources\TimeTracking;
use App\Models\HumanResources\WorkTarget;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait WithTimeTracking{

    public function clockings(): MorphMany
    {
        return $this->morphMany(Clocking::class, 'subject');
    }

    public function timeTrackings(): MorphMany
    {
        return $this->morphMany(TimeTracking::class, 'subject');
    }

    public function createdClockings(): MorphMany
    {
        return $this->morphMany(Clocking::class, 'generator');
    }

    public function deletedClockings(): MorphMany
    {
        return $this->morphMany(Clocking::class, 'deleter');
    }

    public function workTargets(): MorphMany
    {
        return $this->morphMany(WorkTarget::class,'subject');
    }



}

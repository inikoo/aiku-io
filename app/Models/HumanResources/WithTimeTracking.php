<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 10 Mar 2022 01:28:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\HumanResources;


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

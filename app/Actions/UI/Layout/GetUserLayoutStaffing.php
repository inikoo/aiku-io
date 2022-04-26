<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Apr 2022 17:51:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\UI\Layout;


use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;


class GetUserLayoutStaffing extends GetUserLayout
{
    use AsAction;





    protected function initialize($user)
    {
        $this->user = $user;

    }

    protected function getSections($module)
    {
        switch ($module['code']) {

            case 'human_resources':

                $section = Arr::get($module, 'sections', []);

                $section['human_resources.working_hours.interval']['staticParameter']='today';

                return $section;
            default:
                return Arr::get($module, 'sections', []);
        }
    }

}

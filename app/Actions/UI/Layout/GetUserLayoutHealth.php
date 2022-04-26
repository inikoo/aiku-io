<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Apr 2022 17:52:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\UI\Layout;


use Lorisleiva\Actions\Concerns\AsAction;


class GetUserLayoutHealth extends GetUserLayout
{
    use AsAction;





    protected function initialize($user)
    {
        $this->user = $user;




    }



}

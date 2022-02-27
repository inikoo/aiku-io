<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 24 Feb 2022 20:19:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\UI\Layout;


use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @property array $modelsCount
 * @property array $visibleModels
 */
class GetUserLayoutAgent extends GetUserLayout
{
    use AsAction;





    protected function initialize($user)
    {
        $this->user = $user;




    }



}

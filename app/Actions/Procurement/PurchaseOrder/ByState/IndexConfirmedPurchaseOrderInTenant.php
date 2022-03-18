<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 23:23:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\PurchaseOrder\ByState;

use function __;

class IndexConfirmedPurchaseOrderInTenant extends IndexStatePurchaseOrderInTenant
{

    public function setStatePurchaseOrderVariables()
    {
        $this->title = __('Conformed purchase orders');
    }


}


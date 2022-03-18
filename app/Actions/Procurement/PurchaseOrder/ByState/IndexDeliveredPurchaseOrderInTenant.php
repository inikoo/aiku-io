<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 23:24:31 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\PurchaseOrder\ByState;

use function __;

class IndexDeliveredPurchaseOrderInTenant extends IndexStatePurchaseOrderInTenant
{

    public function setStatePurchaseOrderVariables()
    {
        $this->title = __('Delivered purchase orders');
    }


}


<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 23:22:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\PurchaseOrder\ByState;

use function __;

class IndexSubmittedPurchaseOrderInTenant extends IndexStatePurchaseOrderInTenant
{

    public function setStatePurchaseOrderVariables()
    {
        $this->title = __('Submitted purchase orders');
    }


}


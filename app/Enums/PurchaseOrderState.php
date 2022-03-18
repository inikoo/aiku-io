<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 06:18:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Enums;

enum PurchaseOrderState: string
{
    case InProcess = 'in-process';
    case Submitted = 'submitted';
    case Confirmed = 'confirmed';
    case Dispatched = 'dispatched';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

}

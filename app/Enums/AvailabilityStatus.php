<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 01:17:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Enums;

enum AvailabilityStatus: string
{
    case Surplus = 'surplus';
    case Optimal = 'optimal';
    case Low = 'low';
    case Critical = 'critical';
    case OutOfStock = 'out-of-stock';
    case NoApplicable = 'no-applicable';

}

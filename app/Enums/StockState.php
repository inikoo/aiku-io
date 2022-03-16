<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 16 Mar 2022 16:10:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Enums;

enum StockState: string
{
    case Active = 'active';
    case InProcess = 'in-process';
    case Discontinuing = 'discontinuing';
    case Discontinued = 'discontinued';

}

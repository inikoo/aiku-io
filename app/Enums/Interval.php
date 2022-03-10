<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Mar 2022 21:03:57 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Enums;

enum Interval: string
{
    case Today = 'today';
    case Yesterday = 'yesterday';
}

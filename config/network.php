<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 06 Sep 2021 23:28:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


$proxies = [];

if(env('TRUSTED_PROXIES')){
    foreach (explode(',', env('TRUSTED_PROXIES')) as $ip) {
        if(filter_var($ip, FILTER_VALIDATE_IP)){
            $proxies[] = $ip;
        }
    }
}


return [
    'trusted_proxies' => $proxies
];

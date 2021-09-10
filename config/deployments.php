<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 11 Sep 2021 01:40:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

return [

    'repo_path'=>(env('APP_ENV')=='local'?base_path():env('DEPLOYMENT_REPO_PATH'))

];


<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 19 Jan 2022 14:52:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use App\AttributeObservers\ContactNameObserver;
use App\AttributeObservers\TenantLanguageIdObserver;
use App\Models\Account\Tenant;
use App\Models\Helpers\Contact;

return [

    'observers' => [
        Contact::class => [
            ContactNameObserver::class,
        ],
        Tenant::class => [
            TenantLanguageIdObserver::class,
        ],
    ]
];


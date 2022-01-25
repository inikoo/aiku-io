<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 25 Jan 2022 21:53:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\AttributeObservers;


use App\Actions\System\User\HydrateUser;
use App\Models\Account\Tenant;
use App\Models\Helpers\Contact;

class TenantLanguageIdObserver
{

    public function onLanguageIdUpdated(Tenant $tenant)
    {
        $tenant->user->update([
                                  'language_id' => $tenant->language_id
                              ]);


    }
}

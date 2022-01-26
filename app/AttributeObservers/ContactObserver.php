<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 19 Jan 2022 14:48:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\AttributeObservers;

use App\Models\Helpers\Contact;

class ContactObserver
{

    public function onNameUpdated(Contact $contact)
    {
        switch ($contact->contactable_type) {
            case 'Employee':
            case 'Guest':

                /** @var \App\Models\HumanResources\Employee|\App\Models\System\Guest $contactable */
                $contactable = $contact->contactable;
                $contactable->update(['name' => $contact->name]);
                $contactable->user?->update(['name' => $contact->name]);
                break;
        }
    }
    public function onEmailUpdated(Contact $contact)
    {
        switch ($contact->contactable_type) {
            case 'Employee':
            case 'Guest':

                /** @var \App\Models\HumanResources\Employee|\App\Models\System\Guest $contactable */
                $contactable = $contact->contactable;
                $contactable->update(['name' => $contact->name]);
                break;
        }
    }
    public function onPhoneUpdated(Contact $contact)
    {
        switch ($contact->contactable_type) {
            case 'Employee':
            case 'Guest':

                /** @var \App\Models\HumanResources\Employee|\App\Models\System\Guest $contactable */
                $contactable = $contact->contactable;
                $contactable->update(['name' => $contact->name]);
                break;
        }
    }
}

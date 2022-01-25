<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 25 Jan 2022 18:26:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


namespace App\Actions\UI\Localisation;


use Lorisleiva\Actions\Concerns\AsAction;

class GetUITranslations
{
    use AsAction;

    public function handle(): array
    {
        return [
            'dashboard'=>__('Dashboard'),
            'back'=>__('Back'),
            'cancel'=>__('Cancel'),
            'save'=>__('Save'),
            'update'=>__('Update'),
            'log_out'=>__('Log out'),
            'open_sidebar'=>__('Open sidebar'),
            'see_profile'=>__('View profile'),
            'select_a_tab'=>__('Select a tab'),
        ];
    }

}

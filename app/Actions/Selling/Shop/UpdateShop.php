<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 17:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Selling\Shop;

use App\Actions\Migrations\MigrationResult;
use App\Models\Selling\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateShop
{
    use AsAction;

    public function handle(
        Shop $shop,
        array $contactData,
        array $data
    ): MigrationResult {
        $res = new MigrationResult();

        $shop->contact()->update($contactData);
        $res->changes = array_merge($res->changes, $shop->contact->getChanges());

        $shop->update($data);
        $res->changes = array_merge($res->changes, $shop->getChanges());

        $res->model    = $shop;
        $res->model_id = $shop->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}

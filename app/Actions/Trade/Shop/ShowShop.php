<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Jan 2022 03:19:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade\Shop;

use App\Models\Trade\Shop;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;


/**
 * @property Shop $shop
 */
class ShowShop
{
    use AsAction;
    use WithAttributes;

    public function handle()
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.{$this->shop->id}.view");
    }

    public function asController(Shop $shop): Shop
    {
        $this->set('Shop', $shop);
        $this->validateAttributes();

        return $shop;
    }


}

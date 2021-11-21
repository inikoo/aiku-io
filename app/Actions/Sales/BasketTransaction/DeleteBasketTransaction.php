<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 17 Nov 2021 14:51:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\BasketTransaction;

use App\Models\Sales\BasketTransaction;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteBasketTransaction
{
    use AsAction;

    public function handle(BasketTransaction $transaction): ?bool
    {
        return $transaction->delete();
    }
}

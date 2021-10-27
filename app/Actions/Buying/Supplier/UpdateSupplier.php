<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:16:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Buying\Supplier;

use App\Actions\Migrations\MigrationResult;
use App\Models\Buying\Supplier;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSupplier
{
    use AsAction;

    public function handle(Supplier $supplier,array $data, array $contactData): MigrationResult
    {
        $res = new MigrationResult();
        $supplier->contact()->update($contactData);
        $supplier->update($data);
        $res->model    = $supplier;
        $res->model_id = $supplier->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}

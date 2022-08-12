<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Mar 2022 23:12:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;

use App\Actions\Auth\User\StoreUser;
use App\Actions\Auth\User\UpdateUser;
use App\Models\Auth\User;
use App\Models\Utils\ActionResult;
use Exception;
use Illuminate\Support\Arr;


trait WithUser
{


    public function setModel(): void
    {
        $this->model = User::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateUser::run($this->model, Arr::except($this->modelData['user'], ['password']));
    }

    public function storeModel(): ActionResult
    {
        try {

            return StoreUser::run(
                userable: $this->parent,
                userData: $this->modelData['user']
            );
        } catch (Exception $e) {
            $res           = new ActionResult();
            $res->status   = 'error';
            $res->errors[] = $e->getMessage();

            return $res;
        }
    }



}



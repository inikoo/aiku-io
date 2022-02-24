<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 14 Oct 2021 18:02:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\System\User\StoreUser;
use App\Actions\System\User\UpdateUser;
use App\Models\Auth\User;
use Exception;
use Illuminate\Support\Arr;
use App\Models\Utils\ActionResult;


trait WithUser
{


    public function setModel()
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



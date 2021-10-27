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
use App\Models\System\User;
use Exception;
use Illuminate\Support\Arr;


trait WithUser
{


    public function setModel()
    {
        $this->model = User::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateUser::run($this->model, Arr::except($this->modelData['user'], ['password']));
    }

    public function storeModel(): MigrationResult
    {
        try {
            return StoreUser::run(
                userable: $this->parent,
                userData: $this->modelData['user'],
                roles:    $this->modelData['roles']
            );
        } catch (Exception $e) {
            $res           = new MigrationResult();
            $res->status   = 'error';
            $res->errors[] = $e->getMessage();

            return $res;
        }
    }

    protected function migrateImages()
    {
        /** @var User $user */
        $user = $this->model;

        if ($user->userable_type == 'Employee') {
            $auroraModel = 'Staff';
        } else {
            return;
        }
        $auroraImagesCollection          = $this->getModelImagesCollection($auroraModel, $this->parent->aurora_id);
        $auroraImagesCollectionWithImage = $auroraImagesCollection->each(function ($auroraImage) {
            if ($image = MigrateImage::run($auroraImage)) {
                return $auroraImage->image_id = $image->id;
            } else {
                return $auroraImage->image_id = null;
            }
        });

        MigrateImageModels::run($user, $auroraImagesCollectionWithImage);
    }

}



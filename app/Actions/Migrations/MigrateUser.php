<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Oct 2021 15:25:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\System\User\StoreUser;
use App\Actions\System\User\UpdateUser;
use App\Models\HumanResources\Employee;
use App\Models\System\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;


class MigrateUser extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'User Dimension';
        $this->auModel->id_field = 'User Key';
    }

    public function parseModelData()
    {
        $this->modelData['user'] = $this->sanitizeData(
            [
                'username'    => strtolower($this->auModel->data->{'User Handle'}),
                'password'    => Hash::make(config('app.env') == 'local' ? 'hello' : wordwrap(Str::random(), 4, '-', true)),
                'aurora_id'   => $this->auModel->data->{'User Key'},
                'language_id' => $this->parseLanguageID($this->auModel->data->{'User Preferred Locale'}),
                'status'      => $this->auModel->data->{'User Active'} == 'Yes' ? 'active' : 'suspended'
            ]
        );

        $this->auModel->id = $this->auModel->data->{'User Key'};
    }

    public function getParent(): Employee|null
    {
        return match ($this->auModel->data->{'User Type'}) {
            'Staff' => Employee::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'User Parent Key'}),
            default => null
        };
    }

    public function setModel()
    {
        $this->model = User::find($this->auModel->data->aiku_id);
    }

    public function updateModel()
    {
        $this->model = UpdateUser::run($this->model, Arr::except($this->modelData['user'], ['password']));
    }

    public function storeModel(): ?int
    {
        $user        = StoreUser::run($this->parent, $this->modelData['user']);
        $this->model = $user;

        return $user?->id;
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

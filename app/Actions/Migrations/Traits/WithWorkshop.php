<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Mar 2022 23:12:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;


use App\Actions\Production\Workshop\StoreWorkshop;
use App\Actions\Production\Workshop\UpdateWorkshop;
use App\Models\Account\Tenant;
use App\Models\Production\Workshop;
use App\Models\Utils\ActionResult;

use function app;
use function data_set;

trait WithWorkshop
{
    public function getParent(): Tenant
    {
        return app('currentTenant');
    }

    public function setModel()
    {
        $this->model = Workshop::withTrashed()->find($this->auModel->data->aiku_workshop_id);
    }


    public function updateModel(): ActionResult
    {
        /**  @var Workshop $workshop */
        $workshop                                = $this->model;
        $this->modelData['workshop']['data']     = $this->parseMetadata($workshop->data);
        $this->modelData['workshop']['settings'] = $this->parseSettings($workshop->settings);

        return UpdateWorkshop::run(
            workshop:  $workshop,
            modelData: $this->modelData['workshop'],
        );
    }

    public function storeModel(): ActionResult
    {
        $this->modelData['workshop']['data']     = $this->parseMetadata([]);
        $this->modelData['workshop']['settings'] = $this->parseSettings([] );

        return StoreWorkshop::run(
            parent: $this->parent,
            modelData:   $this->modelData['workshop']
        );
    }


    public function parseMetadata($data)
    {
        data_set($data, 'order_id_counter', $this->auModel->data->{'Supplier Order Last Order ID'} ?? null);


        return $data;
    }


    public function parseSettings($settings)
    {
        return $settings;
    }
}



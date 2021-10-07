<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 01:24:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Distribution\Warehouse\StoreWarehouse;
use App\Actions\Distribution\Warehouse\UpdateWarehouse;
use App\Models\Distribution\Warehouse;
use JetBrains\PhpStorm\Pure;

class MigrateWarehouse extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Warehouse Dimension';
        $this->auModel->id_field = 'Warehouse Key';
    }


    public function parseModelData()
    {
        $this->modelData = $this->sanitizeData(
            [
                'name'       => $this->auModel->data->{'Warehouse Name'},
                'code'       => strtolower($this->auModel->data->{'Warehouse Code'}),
                'aurora_id'  => $this->auModel->data->{'Warehouse Key'},
                'created_at' => $this->getDate($this->auModel->data->{'Warehouse Valid From'} ?? null),
            ]
        );
        if (isset($this->auModel->data->{'Warehouse Area Key'})) {
            $this->auModel->id       = $this->auModel->data->{'Warehouse Area Key'};
            $this->auModel->table    = 'Warehouse Area Dimension';
            $this->auModel->id_field = 'Warehouse Area Key';
        } else {
            $this->auModel->id = $this->auModel->data->{'Warehouse Key'};
        }
    }

    public function setModel()
    {
        $this->model = Warehouse::find($this->auModel->data->aiku_id);
    }

    public function updateModel()
    {
        $this->model = UpdateWarehouse::run($this->model, $this->modelData);
    }

    public function storeModel(): ?int
    {
        $warehouse   = StoreWarehouse::run($this->modelData);
        $this->model = $warehouse;

        return $warehouse?->id;
    }


}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 11:59:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Distribution\WarehouseArea\StoreWarehouseArea;
use App\Actions\Distribution\WarehouseArea\UpdateWarehouseArea;
use App\Models\Distribution\Warehouse;
use App\Models\Distribution\WarehouseArea;
use JetBrains\PhpStorm\Pure;

class MigrateWarehouseArea extends MigrateModel
{
    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Warehouse Area Dimension';
        $this->auModel->id_field = 'Warehouse Area Key';
    }

    public function parseModelData()
    {
        $this->modelData   = $this->sanitizeData(
            [
                'name'      => $this->auModel->data->{'Warehouse Area Name'} ?? 'Name not set',
                'code'      => $this->auModel->data->{'Warehouse Area Code'},
                'aurora_id' => $this->auModel->data->{'Warehouse Area Key'},
            ]
        );
        $this->auModel->id = $this->auModel->data->{'Warehouse Area Key'};
    }

    public function getParent(): Warehouse|null
    {
        return (new Warehouse())->firstWhere('aurora_id', $this->auModel->data->{'Warehouse Area Warehouse Key'});
    }

    public function setModel()
    {
        $this->model = WarehouseArea::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel()
    {
        $this->model = UpdateWarehouseArea::run($this->model, $this->modelData);
    }

    public function storeModel(): ?int
    {
        $warehouseArea = StoreWarehouseArea::run($this->parent, $this->modelData);
        $this->model   = $warehouseArea;

        return $warehouseArea?->id;
    }


}

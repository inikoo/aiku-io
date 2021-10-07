<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 05 Oct 2021 00:56:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Distribution\Location\StoreLocation;
use App\Actions\Distribution\Location\UpdateLocation;
use App\Models\Distribution\Location;
use App\Models\Distribution\Warehouse;
use App\Models\Distribution\WarehouseArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateLocation extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Location Dimension';
        $this->auModel->id_field = 'Location Key';
    }

    public function getParent(): Model|Warehouse|WarehouseArea|null
    {
        if ($this->auModel->data->{'Location Warehouse Area Key'}) {
            $auroraWarehouseArea = DB::connection('aurora')->table('Warehouse Area Dimension')->where('Warehouse Area Key', $this->auModel->data->{'Location Warehouse Area Key'})->first();

            if ($auroraWarehouseArea and $auroraWarehouseArea->{'Warehouse Area Place'} == 'Local') {
                return (new WarehouseArea())->firstWhere('aurora_id', $this->auModel->data->{'Location Warehouse Area Key'});
            }
        }

        return (new Warehouse())->firstWhere('aurora_id', $this->auModel->data->{'Location Warehouse Key'});
    }

    public function parseModelData()
    {
        $this->modelData   = [
            'code'      => $this->auModel->data->{'Location Code'},
            'aurora_id' => $this->auModel->data->{'Location Key'},

        ];
        $this->auModel->id = $this->auModel->data->{'Location Key'};
    }


    public function setModel()
    {
        $this->model = Location::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel()
    {
        if (class_basename($this->parent::class) == 'WarehouseArea') {
            $this->modelData['warehouse_id']      = $this->parent->warehouse_id;
            $this->modelData['warehouse_area_id'] = $this->parent->id;
        } else {
            $this->modelData['warehouse_id']      = $this->parent->id;
            $this->modelData['warehouse_area_id'] = null;
        }
        $this->model = UpdateLocation::run($this->model, $this->modelData);
    }

    public function storeModel(): ?int
    {
        $location    = StoreLocation::run($this->parent, $this->modelData);
        $this->model = $location;

        return $location?->id;
    }


}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 18:35:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Inventory\Location\StoreLocation;
use App\Actions\Inventory\Location\UpdateLocation;
use App\Models\Inventory\Location;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateDeletedLocation extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Location Deleted Dimension';
        $this->auModel->id_field = 'Location Deleted Key';
    }

    public function getParent(): Warehouse|WarehouseArea
    {
        if ($this->auModel->data->{'Location Deleted Warehouse Area Key'}) {
            $auroraWarehouseArea = DB::connection('aurora')->table('Warehouse Area Dimension')->where('Warehouse Area Key', $this->auModel->data->{'Location Deleted Warehouse Area Key'})->first();

            if ($auroraWarehouseArea and $auroraWarehouseArea->{'Warehouse Area Place'} == 'Local') {
                return (new WarehouseArea())->firstWhere('aurora_id', $this->auModel->data->{'Location Deleted Warehouse Area Key'});
            }
        }

        return (new Warehouse())->firstWhere('aurora_id', $this->auModel->data->{'Location Deleted Warehouse Key'});
    }

    public function parseModelData()
    {
        $this->modelData = [
            'code'       => $this->auModel->data->{'Location Deleted Code'},
            'aurora_id'  => $this->auModel->data->{'Location Deleted Key'},
            'deleted_at' => $this->auModel->data->{'Location Deleted Date'},
            'state'      => 'deleted'

        ];
        if (!$this->modelData['deleted_at']) {
            print "Deleted Loccation not date";
            dd($this->auModel->data);
        }
        $this->auModel->id = $this->auModel->data->{'Location Deleted Key'};
    }


    public function setModel()
    {
        $this->model = Location::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        if (class_basename($this->parent::class) == 'WarehouseArea') {
            $this->modelData['warehouse_id']      = $this->parent->warehouse_id;
            $this->modelData['warehouse_area_id'] = $this->parent->id;
        } else {
            $this->modelData['warehouse_id']      = $this->parent->id;
            $this->modelData['warehouse_area_id'] = null;
        }

        return UpdateLocation::run($this->model, $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StoreLocation::run($this->parent, $this->modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Location Deleted Dimension')->where('Location Deleted Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}

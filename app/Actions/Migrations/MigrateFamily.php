<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 15 Feb 2022 01:25:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Marketing\Department\StoreDepartment;
use App\Actions\Marketing\Department\UpdateDepartment;
use App\Actions\Marketing\Family\StoreFamily;
use App\Actions\Marketing\Family\UpdateFamily;
use App\Actions\Marketing\Shop\StoreShop;
use App\Actions\Marketing\Shop\UpdateShop;
use App\Models\Marketing\Department;
use App\Models\Marketing\Family;
use App\Models\Marketing\Shop;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateFamily extends MigrateModel
{
    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Category Dimension';
        $this->auModel->id_field = 'Category Key';
        $this->aiku_id_field     = 'aiku_family_id';
    }

    public function parseModelData()
    {
        $this->modelData['family'] = $this->sanitizeData(
            [

                'code' => $this->auModel->data->{'Category Code'} ?? '_',
                'name' => $this->auModel->data->{'Category Label'},

                'created_at' => $this->getDate($this->auModel->data->{'Product Category Valid From'}),
                'aurora_id'  => $this->auModel->data->{'Category Key'},

            ]
        );
        $this->auModel->id         = $this->auModel->data->{'Category Key'};
    }

    public function getParent(): Department|Shop
    {
        $department = (new Department())->firstWhere('aurora_id', $this->auModel->data->{'Product Category Department Category Key'});
        if (is_null($department)) {
            // print "Family ".$this->auModel->data->{'Category Code'}." dont have department  ('.$store->name.') \n";

            return (new Shop())->firstWhere('aurora_id', $this->auModel->data->{'Product Category Store Key'});
        } else {
            return $department;
        }
    }

    public function setModel()
    {
        $this->model = Family::find($this->auModel->data->aiku_family_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateFamily::run(family: $this->model, modelData: $this->modelData['family']);
    }

    public function storeModel(): ActionResult
    {
        return StoreFamily::run($this->parent, modelData: $this->modelData['family']);
    }

    public function postMigrateActions(ActionResult $res): ActionResult
    {
        /*
        foreach (
            DB::connection('aurora')
                ->table('Category Dimension')
                ->leftJoin('Product Category Dimension', 'Product Category Key', 'Category Key')
                ->where('Category Branch Type', 'Head')
                ->where('Category Root Key', $this->auModel->data->{'Store Department Category Key'})->get() as $auroraDepartment
        ) {
        }
*/
        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Category Dimension')->where('Category Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }

}

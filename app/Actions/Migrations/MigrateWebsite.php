<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 02:02:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Web\Website\StoreWebsite;
use App\Actions\Web\Website\UpdateWebsite;
use App\Models\Marketing\Shop;
use App\Models\Web\Website;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateWebsite extends MigrateModel
{
    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Website Dimension';
        $this->auModel->id_field = 'Website Key';
    }

    public function parseModelData()
    {
        $status = match ($this->auModel->data->{'Website Status'}) {
            'Maintenance' => 'maintenance',
            'Closed' => 'closed',
            default => 'construction',
        };

        $this->modelData['website'] = $this->sanitizeData(
            [

                'name'        => $this->auModel->data->{'Website Name'},
                'code'        => $this->auModel->data->{'Website Code'},
                'url'         => strtolower($this->auModel->data->{'Website URL'}),
                'status'      => $status,
                'launched_at' => $this->getDate($this->auModel->data->{'Website Launched'}),
                'created_at'  => $this->getDate($this->auModel->data->{'Website From'}),
                'aurora_id'   => $this->auModel->data->{'Website Key'},

            ]
        );
        $this->auModel->id          = $this->auModel->data->{'Website Key'};
    }

    public function getParent(): Shop
    {
        return Shop::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Website Store Key'});
    }

    public function setModel()
    {
        $this->model = Website::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateWebsite::run(website: $this->model, modelData: $this->modelData['website']);
    }

    public function storeModel(): ActionResult
    {
        return StoreWebsite::run(shop: $this->parent, data: $this->modelData['website']);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Website Dimension')->where('Website Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }

}

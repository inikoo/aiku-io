<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 16:48:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Selling\Shop\StoreShop;
use App\Actions\Selling\Shop\UpdateShop;
use App\Models\Selling\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateShop extends MigrateModel
{
    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Store Dimension';
        $this->auModel->id_field = 'Store Key';
    }

    public function parseModelData()
    {
        $this->modelData   = $this->sanitizeData(
            [
                'type'        => strtolower($this->auModel->data->{'Store Type'}),
                'name'        => $this->auModel->data->{'Store Name'},
                'code'        => strtolower($this->auModel->data->{'Store Code'}),
                'language_id' => $this->parseLanguageID($this->auModel->data->{'Store Locale'}),
                'currency_id' => $this->parseCurrencyID($this->auModel->data->{'Store Currency Code'}),
                'timezone_id' => $this->parseTimezoneID($this->auModel->data->{'Store Timezone'}),
                'aurora_id'   => $this->auModel->data->{'Store Key'},
                'url'         => $this->auModel->data->{'Store URL'},
                'state'       => Str::snake($this->auModel->data->{'Store Status'} == 'Normal' ? 'Open' : $this->auModel->data->{'Store Status'}, '-'),
                'open_at'     => $this->getDate($this->auModel->data->{'Store Valid From'}),
                'closed_at'   => $this->getDate($this->auModel->data->{'Store Valid To'}),
            ]
        );
        $this->auModel->id = $this->auModel->data->{'Store Key'};
    }


    public function setModel()
    {
        $this->model = Shop::find($this->auModel->data->aiku_id);
    }

    public function updateModel()
    {
        $this->model = UpdateShop::run($this->model, $this->modelData);
    }

    public function storeModel(): ?int
    {
        $shop        = StoreShop::run($this->modelData);
        $this->model = $shop;

        return $shop?->id;
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraModelID): array
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        $auroraData = DB::connection('aurora')->table('Store Dimension')->where('Store Key', $auroraModelID)->get();

        return $this->handle($auroraData);
    }

}

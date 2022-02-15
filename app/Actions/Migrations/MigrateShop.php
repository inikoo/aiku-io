<?php
/*
 3*  Author: Raul Perusquia <raul@inikoo.com>
 *4  Created: Sat, 25 Sep 2021 16:48:42 Malaysia Time, Kuala Lumpur, Malaysia
 * 5 Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Trade\Shop\StoreShop;
use App\Actions\Trade\Shop\UpdateShop;
use App\Models\Trade\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

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

        $this->modelData['shop'] = $this->sanitizeData(
            [
                'type'                     =>
                    match ($this->auModel->data->{'Store Type'}) {
                        'Dropshipping', 'Fulfilment' => 'fulfilment_house',
                        default => 'shop'
                    },
                'subtype'                  => strtolower($this->auModel->data->{'Store Type'}),
                'code'                     => $this->auModel->data->{'Store Code'},
                'name'                     => $this->auModel->data->{'Store Name'},
                'website'                  => $this->auModel->data->{'Store URL'},
                'company_name'             => $this->auModel->data->{'Store Company Name'},
                'contact_name'             => $this->auModel->data->{'Store Contact Name'},
                'email'                    => $this->auModel->data->{'Store Email'},
                'phone'                    => $this->auModel->data->{'Store Telephone'},
                'tax_number'               => $this->auModel->data->{'Store VAT Number'},
                'identity_document_number' => $this->auModel->data->{'Store Company Number'},
                'tax_number_status'        => 'valid',


                'language_id' => $this->parseLanguageID($this->auModel->data->{'Store Locale'}),
                'currency_id' => $this->parseCurrencyID($this->auModel->data->{'Store Currency Code'}),
                'timezone_id' => $this->parseTimezoneID($this->auModel->data->{'Store Timezone'}),
                'aurora_id'   => $this->auModel->data->{'Store Key'},
                'state'       => Str::snake($this->auModel->data->{'Store Status'} == 'Normal' ? 'Open' : $this->auModel->data->{'Store Status'}, '-'),
                'open_at'     => $this->getDate($this->auModel->data->{'Store Valid From'}),
                'closed_at'   => $this->getDate($this->auModel->data->{'Store Valid To'}),
                'created_at'  => $this->getDate($this->auModel->data->{'Store Valid From'}),

            ]
        );
        $this->auModel->id       = $this->auModel->data->{'Store Key'};
    }


    public function setModel()
    {
        $this->model = Shop::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateShop::run(shop: $this->model, modelData: $this->modelData['shop']);
    }

    public function storeModel(): ActionResult
    {
        return StoreShop::run(modelData: $this->modelData['shop']);
    }



    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Store Dimension')->where('Store Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }

}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 14:21:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Models\Buying\Agent;
use App\Models\Buying\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateSupplier extends MigrateModel
{
    use WithSupplier;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Supplier Dimension';
        $this->auModel->id_field = 'Supplier Key';
    }


    public function getParent()
    {
        if ($this->auModel->data->{'Supplier Has Agent'} == 'Yes') {
            $res = DB::connection('aurora')->table('Agent Supplier Bridge')
                ->leftJoin('Agent Dimension', 'Agent Supplier Agent Key', '=', 'Agent Key')
                ->where('Agent Supplier Supplier Key', $this->auModel->data->{'Supplier Key'})
                ->select('aiku_id')
                ->first();


            return Agent::findOrFail($res->aiku_id);
        } else {
            return app('currentTenant');
        }
    }






    public function parseModelData()
    {
        $deleted_at = $this->auModel->data->{'Supplier Valid To'};
        if ($this->auModel->data->{'Supplier Type'} != 'Archived') {
            $deleted_at = null;
        }
        $phone = $this->auModel->data->{'Supplier Main Plain Mobile'};
        if ($phone == '') {
            $phone = $this->auModel->data->{'Supplier Main Plain Telephone'};
        }

        //print_r($this->auModel->data);

        $this->modelData['contact'] = $this->sanitizeData(
            [
                'company'    => $this->auModel->data->{'Supplier Company Name'},
                'name'       => $this->auModel->data->{'Supplier Main Contact Name'},
                'email'      => $this->auModel->data->{'Supplier Main Plain Email'},
                'phone'      => $phone,
                'created_at' => $this->auModel->data->{'Supplier Valid From'}
            ]
        );

        $this->modelData['supplier'] = $this->sanitizeData(
            [
                'name' => $this->auModel->data->{'Supplier Name'},
                'code' => Str::snake(
                    preg_replace('/^aw/', 'aw ', strtolower($this->auModel->data->{'Supplier Code'}))
                    ,
                    '-'
                ),

                'currency_id' => $this->parseCurrencyID($this->auModel->data->{'Supplier Default Currency Code'}),
                'aurora_id'   => $this->auModel->data->{'Supplier Key'},
                'created_at'  => $this->auModel->data->{'Supplier Valid From'},
                'deleted_at'  => $deleted_at,

            ]
        );

        $this->modelData['address'] = $this->parseAddress(prefix: 'Supplier Contact', auAddressData: $this->auModel->data);


        $this->auModel->id = $this->auModel->data->{'Supplier Key'};
    }


    public function setModel()
    {
        $this->model = Supplier::withTrashed()->find($this->auModel->data->aiku_id);
    }



    protected function migrateImages()
    {
        /**  @var Supplier $supplier */
        $supplier                        = $this->model;
        $auroraImagesCollection          = $this->getModelImagesCollection('Supplier', $supplier->aurora_id);
        $auroraImagesCollectionWithImage = $auroraImagesCollection->each(function ($auroraImage) {
            if ($image = MigrateImage::run($auroraImage)) {
                return $auroraImage->image_id = $image->id;
            } else {
                return $auroraImage->image_id = null;
            }
        });

        MigrateImageModels::run($supplier, $auroraImagesCollectionWithImage);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Supplier Dimension')->where('Supplier Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 28 Oct 2021 02:29:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Models\Trade\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateDeletedSupplier extends MigrateModel
{
    use WithSupplier;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Supplier Deleted Dimension';
        $this->auModel->id_field = 'Supplier Deleted Key';
    }

    public function parseModelData()
    {
        $auroraDeletedData = json_decode(gzuncompress($this->auModel->data->{'Supplier Deleted Metadata'}));


        $phone = $auroraDeletedData->{'Supplier Main Plain Mobile'}??null;
        if ($phone == '') {
            $phone = $auroraDeletedData->{'Supplier Main Plain Telephone'};
        }

        $deleted_at=$this->auModel->data->{'Supplier Deleted Date'};


        $this->modelData['contact'] = $this->sanitizeData(
            [
                'company'    => $auroraDeletedData->{'Supplier Company Name'},
                'name'       => $auroraDeletedData->{'Supplier Main Contact Name'},
                'email'      => $auroraDeletedData->{'Supplier Main Plain Email'},
                'phone'      => $phone,
                'created_at' => $auroraDeletedData->{'Supplier Valid From'}??null
            ]
        );

        $this->modelData['supplier'] = $this->sanitizeData(
            [
                'name' => $auroraDeletedData->{'Supplier Name'},
                'code' => Str::snake(
                    preg_replace('/^aw/', 'aw ', strtolower($auroraDeletedData->{'Supplier Code'}))
                    ,
                    '-'
                ),

                'currency_id' => $this->parseCurrencyID($auroraDeletedData->{'Supplier Default Currency Code'}),
                'aurora_id'   => $auroraDeletedData->{'Supplier Key'},
                'created_at'  => $auroraDeletedData->{'Supplier Valid From'}??null,
                'deleted_at'  => $deleted_at,

            ]
        );

        $this->modelData['address'] = $this->parseAddress(prefix: 'Supplier Contact', auAddressData: $auroraDeletedData);


        $this->auModel->id = $this->auModel->data->{'Supplier Deleted Key'};
    }

    public function getParent(): Model|Shop|Builder|\Illuminate\Database\Query\Builder|null
    {
        return app('currentTenant');
    }





    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }


    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Supplier Deleted Dimension')->where('Supplier Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }

}

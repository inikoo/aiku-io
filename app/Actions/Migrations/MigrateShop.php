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
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateShop
{
    use AsAction;
    use MigrateAurora;

    public function handle($auroraData): array
    {
        $result = [
            'updated'  => 0,
            'inserted' => 0,
            'errors'   => 0
        ];

        $storeData = [
            'type'        => $auroraData->{'Store Type'},
            'name'        => $auroraData->{'Store Name'},
            'code'        => strtolower($auroraData->{'Store Code'}),
            'language_id' => $this->parseLanguageID($auroraData->{'Store Locale'}),
            'currency_id' => $this->parseCurrencyID($auroraData->{'Store Currency Code'}),
            'timezone_id' => $this->parseTimezoneID($auroraData->{'Store Timezone'}),
            'aurora_id'   => $auroraData->{'Store Key'},
            'url'         => $auroraData->{'Store URL'},
            'state'       => $auroraData->{'Store Status'} == 'Normal' ? 'Open' : $auroraData->{'Store Status'},
            'open_at'     => $this->getDate($auroraData->{'Store Valid From'}),
            'closed_at'   => $this->getDate($auroraData->{'Store Valid To'}),

        ];
        //print_r($storeData);

        $storeData=$this->sanitizeData($storeData);


        if ($auroraData->aiku_id) {
            $shop = Shop::find($auroraData->aiku_id);

            if ($shop) {
                $shop = UpdateShop::run($shop, $storeData);


                $changes = $shop->getChanges();
                if (count($changes) > 0) {
                    $result['updated']++;
                }
            } else {
                $result['errors']++;
                DB::connection('aurora')->table('Staff Dimension')
                    ->where('Staff Key', $auroraData->{'Staff Key'})
                    ->update(['aiku_id' => null]);
            }


        } else {
            $store = StoreShop::run($storeData);
            if ($store) {
                DB::connection('aurora')->table('Store Dimension')
                    ->where('Store Key', $auroraData->{'Store Key'})
                    ->update(['aiku_id' => $store->id]);
                $result['inserted']++;
            }
        }

        return $result;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraModelID): array
    {
        $this->set_aurora_connection(app('currentTenant')->data['aurora_db']);
        $auroraData = DB::connection('aurora')->table('Store Dimension')->where('Store Key', $auroraModelID)->get();

        return $this->handle($auroraData);
    }

}

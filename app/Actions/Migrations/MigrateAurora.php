<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 03:16:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait MigrateAurora
{


    private function set_aurora_connection($database_name)
    {
        $database_settings = data_get(config('database.connections'), 'aurora');
        data_set($database_settings, 'database', $database_name);
        config(['database.connections.aurora' => $database_settings]);
        DB::connection('aurora');
        DB::purge('aurora');
    }

    private function sanitizeData($date): array
    {
        return array_filter($date, fn($value) => !is_null($value) && $value !== ''
            && $value != '0000-00-00 00:00:00'
            && $value != '2018-00-00 00:00:00'
        );
    }

    private function getDate($value): string
    {
        return ($value != '' && $value != '0000-00-00 00:00:00'
            && $value != '2018-00-00 00:00:00') ? Carbon::parse($value)->format('Y-m-d') : '';
    }

    private function parseLanguageID($locale): int|null
    {
        if ($locale != '') {
            $locale = substr($locale, 0, 2);

            return Language::where('code', $locale)->first()->id;
        }

        return null;
    }

    private function parseCurrencyID($currencyCode): int|null
    {
        if ($currencyCode != '') {
            if ($currencyCode == 'LEU') {
                $currencyCode = 'RON';
            }

            return Currency::where('code', $currencyCode)->firstOrFail()->id;
        }

        return null;
    }

    private function parseTimezoneID($timezone): int|null
    {
        if ($timezone != '') {
            return Timezone::where('name', $timezone)->first()->id;
        }

        return null;
    }

    private function parseCountryID($country): int|null
    {
        if ($country != '') {
            try {
                return Country::withTrashed()->where('code', $country)->firstOrFail()->id;
            } catch (Exception) {
                print "Country $country not found\n";

                return null;
            }
        }

        return null;
    }

    private function parseAddress($prefix, $auroraData): array
    {
        $addressData                        = [];
        $addressData['address_line_1']      = Str::of($auroraData->{$prefix.' Address Line 1'})->limit(251);
        $addressData['address_line_2']      = Str::of($auroraData->{$prefix.' Address Line 2'})->limit(251);
        $addressData['sorting_code']        = Str::of($auroraData->{$prefix.' Address Sorting Code'})->limit(187);
        $addressData['postal_code']         = Str::of($auroraData->{$prefix.' Address Postal Code'})->limit(187);
        $addressData['locality']            = Str::of($auroraData->{$prefix.' Address Locality'})->limit(187);
        $addressData['dependant_locality']  = Str::of($auroraData->{$prefix.' Address Dependent Locality'})->limit(187);
        $addressData['administrative_area'] = Str::of($auroraData->{$prefix.' Address Administrative Area'})->limit(187);
        $addressData['country_id']          = $this->parseCountryID($auroraData->{$prefix.' Address Country 2 Alpha Code'});

        return $addressData;
    }


    protected function getModelImagesCollection($model, $id): Collection
    {
        return DB::connection('aurora')
            ->table('Image Subject Bridge')
            ->leftJoin('Image Dimension', 'Image Subject Image Key', '=', 'Image Key')
            ->where('Image Subject Object', $model)
            ->where('Image Subject Object Key', $id)
            ->orderByRaw("FIELD(`Image Subject Is Principal`, 'Yes','No')")
            ->get();
    }

    private function updateAuroraModel($table, $table_id_field, $id, $value = null)
    {
        DB::connection('aurora')->table($table)
            ->where($table_id_field, $id)
            ->update(['aiku_id' => $value]);
    }

    protected function process($auroraData): array
    {
        $result = [
            'updated'  => 0,
            'inserted' => 0,
            'errors'   => 0
        ];


        $parent = $this->getParent($auroraData);

        $modelData = $this->getModelData($auroraData, $parent);


        if ($auroraData->aiku_id) {
            $model = $this->getModel($auroraData);
            if ($model) {
                $model = $this->updateModel($model, $modelData);

                $changes = $model->getChanges();
                if (count($changes) > 0) {
                    $result['updated']++;
                }
            } else {
                $result['errors']++;

                $this->updateAuroraModel();
                /*
               DB::connection('aurora')->table('Location Dimension')
                   ->where('Location Key', $auroraData->{'Location Key'})
                   ->update(['aiku_id' => null]);
*/

               return $result;
           }
       } else {
           $model = $this->storeModel();

           if (!$model) {
               $result['errors']++;

               return $result;
           }
           $this->updateAuroraModel();
           /*
           DB::connection('aurora')->table('Location Dimension')
               ->where('Location Key', $auroraData->{'Location Key'})
               ->update(['aiku_id' => $location->id]);
*/
            $result['inserted']++;
        }


        return $result;
    }


}

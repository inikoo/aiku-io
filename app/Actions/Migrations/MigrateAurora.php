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
                return Country::where('code', $country)->firstOrFail()->id;
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
        $addressData['address_line_1']      = Str::of($auroraData->{$prefix.' Address Line 1'})->limit(191);
        $addressData['address_line_2']      = Str::of($auroraData->{$prefix.' Address Line 2'})->limit(191);
        $addressData['sorting_code']        = Str::of($auroraData->{$prefix.' Address Sorting Code'})->limit(191);
        $addressData['postal_code']         = Str::of($auroraData->{$prefix.' Address Postal Code'})->limit(191);
        $addressData['locality']            = Str::of($auroraData->{$prefix.' Address Locality'})->limit(191);
        $addressData['dependant_locality']  = Str::of($auroraData->{$prefix.' Address Dependent Locality'})->limit(191);
        $addressData['administrative_area'] = Str::of($auroraData->{$prefix.' Address Administrative Area'})->limit(191);
        $addressData['country_id']          = $this->parseCountryID($auroraData->{$prefix.' Address Country 2 Alpha Code'});

        return $addressData;
    }

    /*
    protected function getImageData($tenant, $legacy_image_key)
    {
        $sql = "* from `Image Dimension` I   where  `Image Key`=?";
        foreach (
            DB::connection('legacy')->select(
                "select $sql ", [$legacy_image_key]
            ) as $image_legacy_data
        ) {
            $image_filename_data = get_image_filename_legacy($tenant, $image_legacy_data);
            if ($image_filename_data) {
                return create_image_from_legacy($tenant, $image_legacy_data, $image_filename_data);
            }
        }

        return false;
    }
*/

    protected function getModelImagesCollection($model, $id): Collection
    {


            return DB::connection('aurora')
                ->table('Image Subject Bridge')
                ->leftJoin('Image Dimension', 'Image Subject Image Key', '=', 'Image Key')
                ->where('Image Subject Object', $model)
                ->where('Image Subject Object Key', $id)
                ->orderByRaw("FIELD(`Image Subject Is Principal`, 'Yes','No')")
                ->get() ;
    }




}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 03:16:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */
namespace App\Actions\Migrations;

use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            if($currencyCode=='LEU'){
                $currencyCode='RON';
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

}

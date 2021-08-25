<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 25 Aug 2021 23:19:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;


use App\Models\Assets\Country;
use App\Models\Assets\Language;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{

    /**
     * @throws \Exception
     */
    public function run()
    {




        foreach (DateTimeZone::listIdentifiers() as $identifier) {
            $tz = new DateTimeZone($identifier);

            $tz_location = $tz->getLocation();

            $data = [];

            $country = Country::where('code', $tz_location['country_code'])->first();

            $country_id = null;
            if ($country) {
                $country_id = $country->id;
            } else {
                $data['fail_country_code'] = $tz_location['country_code'];
            }


            Language::UpdateOrCreate(
                ['name' => $tz->getName()],
                [
                    'offset'     => $tz->getOffset(new DateTime("now", new DateTimeZone("UTC"))),
                    'latitude'   => $tz_location['latitude'],
                    'longitude'  => $tz_location['longitude'],
                    'location'   => ($tz_location['comments'] == '?' ? '' : $tz_location['comments']),
                    'data'       => $data,
                    'country_id' => $country_id
                ]
            );
        }
        foreach (DateTimeZone::listAbbreviations() as $abbreviation => $abbreviationData) {
            foreach ($abbreviationData as $timezoneData) {
                if ($timezone = Language::where('name', $timezoneData['timezone_id'])->first()) {
                    $data          = $timezone->data;
                    $abbreviations = data_get($data, 'abbreviations', []);
                    array_push($abbreviations, $abbreviation);
                    data_set($data, 'abbreviations', array_unique($abbreviations));
                    $timezone->data = $data;
                    $timezone->save();
                }
            }
        }

    }


}

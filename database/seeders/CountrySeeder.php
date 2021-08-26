<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 23 Aug 2021 18:02:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Assets\Country;
use CommerceGuys\Addressing\Country\CountryRepository;
use Exception;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countriesData = [];

        foreach (['continent', 'names', 'iso3', 'capital', 'phone'] as $field) {
            foreach (json_decode(file_get_contents("http://country.io/$field.json")) as $countryCode => $value) {
                $countriesData[$countryCode][$field] = $value;
            }
        }

        foreach ($countriesData as $countryCode => $countryData) {
            Country::UpdateOrCreate(
                ['code' => $countryCode],
                [
                    'name'       => $countryData['names'],
                    'iso3'       => $countryData['iso3'],
                    'continent'  => $countryData['continent'],
                    'capital'    => $countryData['capital'],
                    'phone_code' => $countryData['phone'],
                ]
            );
        }
        $countryRepository = new CountryRepository();

        $countryList = $countryRepository->getList('en-GB');
        foreach ($countryList as $countryCode => $countryName) {
            $_country = $countryRepository->get($countryCode);


            Country::UpdateOrCreate(
                ['code' => $countryCode],
                [
                    'name'   => $countryName,
                    'iso3'   => $_country->getThreeLetterCode()
                ]
            );
        }
    }
}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 23 Aug 2021 18:02:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Helpers\Country;
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
        $row = 1;
        $handle = fopen(__DIR__."/../../resources/data/countryData.csv", "r");
        if ($handle !== false) {
            while (($data = fgetcsv($handle, 1000)) !== false) {
                if ($row > 1) {

                    try {

                        $country                   = new Country();
                        $country->name             = $data[0];
                        $country->code             = $data[1];
                        $country->code_iso3        = $data[2];
                        $country->code_iso_numeric = $data[5];
                        $country->continent        = $data[9];
                        $country->capital          = $data[10];
                        $country->timezone         = $data[11];
                        $country->phone_code       = $data[8];
                        $country->geoname_id       = (is_numeric($data[6]) ? $data[6] : null);
                        $country->data             = [
                            'GDP'           => $data[20],
                            'Area'          => $data[15],
                            'E164'          => $data[7],
                            'FIPS'          => $data[4],
                            'InternetUsers' => $data[17],

                        ];
                        $country->save();
                    }catch (Exception){
                        //
                    }


                }

                $row++;
            }
            fclose($handle);
        }
    }
}

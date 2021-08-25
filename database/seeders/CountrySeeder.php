<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 23 Aug 2021 18:02:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Assets\Country;
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
        $row    = 1;
        $handle = fopen(__DIR__."/datasets/countryData.csv", "r");
        if ($handle !== false) {
            while (($data = fgetcsv($handle, 1000)) !== false) {
                if ($row > 1) {
                    try {


                        Country::UpdateOrCreate(
                            ['code' => $data[1]],
                            [
                                'name'             => $data[0],
                                'code_iso3'        => $data[2],
                                'code_iso_numeric' =>  (is_numeric($data[6]) ? $data[5] : null),
                                'continent'        => $data[9],
                                'capital'          => $data[10],
                                'phone_code'       => $data[8],
                                'geoname_id'       => (is_numeric($data[6]) ? $data[6] : null),

                                'data' => [
                                    'GDP'           => $data[20],
                                    'Area'          => $data[15],
                                    'E164'          => $data[7],
                                    'FIPS'          => $data[4],
                                    'InternetUsers' => $data[17],

                                ]

                            ]
                        );
                    } catch (Exception $e) {
                        print_r($e->getMessage());
                    }
                }

                $row++;
            }
            fclose($handle);
        }
    }
}

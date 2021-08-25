<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 25 Aug 2021 23:17:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Assets\Country;
use App\Models\Assets\Timezone;
use Exception;
use Illuminate\Database\Seeder;

class CountryTimezoneSeeder extends Seeder
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
                    if($country=Country::where('code',$data[1])->first()){

                        if($timezone=Timezone::where('name',$data[11])->first()){
                            $country->timezone_id=$timezone->id;
                            $country->save();
                        }else{
                            print "Timezone not found : {$data[11]}\n";
                        }

                    }else{
                        print "Country not found : {$data[1]}\n";
                    }

                }

                $row++;
            }
            fclose($handle);
        }
    }
}

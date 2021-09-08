<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 23:18:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RootUserSeeder extends Seeder
{

    public function run()
    {
        //$password  = (App::environment('local') ? 'hello' : wordwrap(Str::random(12), 4, '-', true));
        $password='hello';
        $root_user = (new User())->updateOrCreate(['email' => 'root@aiku'], ['name' => 'Admin Account', 'password' => Hash::make($password)]);
        $root_user->assignRole('super-admin');
    }
}

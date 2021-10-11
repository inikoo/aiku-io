<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 13:04:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\Account;

use App\Actions\Account\AccountUser\StoreAccountUser;
use App\Models\Account\AccountAdmin;
use App\Models\Aiku\Aiku;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class Install extends Command
{

    protected $signature = 'admin:install';

    protected $description = 'Install aiku';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $aiku = new Aiku([]);

        $aiku->save();


        return 0;
    }
}

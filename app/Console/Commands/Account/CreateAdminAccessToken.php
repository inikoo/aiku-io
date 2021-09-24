<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 23:16:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\Account;

use App\Models\Account\AccountAdmin;
use Illuminate\Console\Command;


class CreateAdminAccessToken extends Command
{

    protected $signature = 'admin:token {slug} {token_name} {scopes?*} ';

    protected $description = 'Create new admin access token';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($admin = AccountAdmin::firstWhere('slug', $this->argument('slug'))) {
            $token= $admin->accountUser->createToken($this->argument('token_name'),$this->argument('scopes'))->plainTextToken;
            $this->line("AccountAdmin access token: $token");
        } else {
            $this->error("AccountAdmin not found: {$this->argument('slug')}");
        }

        return 0;
    }
}

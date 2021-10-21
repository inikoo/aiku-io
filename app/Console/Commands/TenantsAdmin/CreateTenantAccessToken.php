<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 23:16:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\TenantsAdmin;

use App\Models\Account\Tenant;
use Illuminate\Console\Command;


class CreateTenantAccessToken extends Command
{

    protected $signature = 'tenant:token {nickname} {token_name} {scopes?*} ';

    protected $description = 'Create new tenant access token';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($tenant = Tenant::firstWhere('nickname', $this->argument('nickname'))) {
            $tenant->makeCurrent();
            $token= $tenant->user->createToken($this->argument('token_name'),$this->argument('scopes'))->plainTextToken;
            $this->line("Tenant access token: $token");
        } else {
            $this->error("Tenant not found: {$this->argument('nickname')}");
        }

        return 0;
    }
}

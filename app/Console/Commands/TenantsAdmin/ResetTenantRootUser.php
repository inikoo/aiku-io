<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 15 Aug 2021 04:53:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\TenantsAdmin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use App\Models\Tenant;
use Illuminate\Console\Command;

class ResetTenantRootUser extends Command
{
    use TenantAware;


    protected $signature = 'tenant:reset_root {--tenant=*}';

    protected $description = 'Create or reset tenant root password';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        /** @var Tenant $tenant */
        $tenant = Tenant::current();

        $this->line('Tenant  '.$tenant->name);

        $password='hello';

        (new User())->updateOrCreate(
            ['email' => 'root@aiku'],
            ['name' => 'Admin Account','password'=>Hash::make($password)]
        );
    }
}

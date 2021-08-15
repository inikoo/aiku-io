<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 15 Aug 2021 04:53:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\TenantsAdmin;

use App\Models\Tenant;
use Illuminate\Console\Command;

class CreateTenant extends Command
{

    protected $signature = 'tenant:new {name} {domain} {database}';

    protected $description = 'Create new tenant';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $tenant = new Tenant([
                                 'domain'   => $this->argument('domain'),
                                 'database' => $this->argument('database'),
                                 'name'     => $this->argument('name'),
                                 'data'     => []
                             ]);

        $tenant->save();


        return 0;
    }
}

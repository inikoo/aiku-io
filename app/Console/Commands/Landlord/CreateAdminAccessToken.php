<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 23:16:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\Landlord;

use App\Models\Aiku\Admin;
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
        if ($admin = Admin::firstWhere('slug', $this->argument('slug'))) {
            $token= $admin->user->createToken($this->argument('token_name'),$this->argument('scopes'))->plainTextToken;
            $this->line("Admin access token: $token");
        } else {
            $this->error("Admin not found: {$this->argument('slug')}");
        }

        return 0;
    }
}

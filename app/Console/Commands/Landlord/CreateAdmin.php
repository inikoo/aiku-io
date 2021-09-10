<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 23:16:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\Landlord;

use App\Models\Aiku\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class CreateAdmin extends Command
{

    protected $signature = 'admin:new {--randomPassword} {name} {email} {slug?}';

    protected $description = 'Create new admin';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if($this->option('randomPassword')){
            $password = Str::random(32);
        }else{
            $password = $this->secret('What is the password?');
        }

        if(strlen($password)<8){
            $this->error("Password needs to be at least 8 characters");

            return 0;
        }

        $admin = new Admin([
                               'name'     => $this->argument('name'),
                               'email'    => $this->argument('email'),
                               'password' => Hash::make($password)

                           ]);
        if($this->argument('slug')){
            $admin->slug=$this->argument('slug');
        }

        $admin->save();


         $this->line("Admin created $admin->slug");


        return 0;
    }
}

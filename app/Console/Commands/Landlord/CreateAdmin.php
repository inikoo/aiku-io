<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 23:16:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\Landlord;

use App\Models\Aiku\Admin;
use App\Models\Aiku\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class CreateAdmin extends Command
{

    protected $signature = 'admin:new {--randomPassword} {name} {email} {slug?} {username?}';

    protected $description = 'Create new admin';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($this->option('randomPassword')) {
            $password = (config('app.env') == 'local' ? 'hello' : wordwrap(Str::random(), 4, '-', true));

        } else {
            $password = $this->secret('What is the password?');
            if (strlen($password) < 8) {
                $this->error("Password needs to be at least 8 characters");
                return 0;
            }
        }



        $admin = new Admin([
                               'name' => $this->argument('name'),
                               'email' => $this->argument('email'),


                           ]);
        if ($this->argument('slug')) {
            $admin->slug = $this->argument('slug');
        }
        $username = $admin->slug;
        if ($this->argument('username')) {
            $username = $this->argument('slug');
        }

        $admin->save();


        $user = new User([
                             'username' => $username,
                             'password' => Hash::make($password)
                         ]);

        $admin->user()->save($user);

        $this->line("Admin created $admin->slug");

        $this->table(
            ['Code', 'Username', 'Password'],
            [
                [

                    $admin->slug,
                    $user->username,
                    ($this->option('randomPassword') ? $password : '*****'),
                ],

            ]
        );


        return 0;
    }
}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 23:16:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\Account;


use App\Actions\Admin\AdminUser\StoreAdminUser;
use App\Models\Admin\AccountAdmin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class CreateAccountAdmin extends Command
{

    protected $signature = 'admin:new {--randomPassword} {name} {email} {slug?} {username?}';

    protected $description = 'Create new admin';

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


        $admin = new AccountAdmin([
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


        $res = StoreAdminUser::run($admin,
                                    [
                                          'username' => $username,
                                          'password' => Hash::make($password)
                                      ]
        );



        $this->line("Account admin created $admin->slug");

        $this->table(
            ['Code', 'Username', 'Password'],
            [
                [
                    $admin->slug,
                    $res->model->username,
                    ($this->option('randomPassword') ? $password : '*****'),
                ],

            ]
        );


        return 0;
    }
}

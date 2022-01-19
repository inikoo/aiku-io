<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 17 Jan 2022 18:59:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\User;

use App\Models\Account\Tenant;
use App\Models\System\User;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;


class HydrateUser
{
    use AsAction;

    public string $commandSignature = 'hydrate:user {user_id} {--t|tenant=* : Tenant nickname}';

    public function handle(User $user): void
    {
        $user->update(
            ['name' => $user->userable->name]
        );
    }

    public function asCommand(Command $command): void
    {

        $tenants = match ($command->option('tenant')) {
            [] => Tenant::all(),
            default => Tenant::where('nickname',$command->option('tenant'))->get()
        };

        $tenants->eachCurrent(function(Tenant $tenant) use ($command) {
            $command->info("Tenant: $tenant->nickname");

            if ($command->argument('user_id') == 'all') {
                $this->loopAll($command);
            } else {
                $this->handle(User::findOrFail($command->argument('user_id')));

                $command->info('Done!');
            }

        });



    }


    protected function loopAll(Command $command)
    {
        $command->withProgressBar(User::all(), function ($user) {
            $this->handle($user);
        });
        $command->info("");
    }

}



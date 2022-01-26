<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 17 Jan 2022 18:59:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Guest;

use App\Models\Account\Tenant;
use App\Models\System\Guest;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;


class HydrateGuest
{
    use AsAction;

    public string $commandSignature = 'hydrate:guest {guest_id} {--t|tenant=* : Tenant nickname}';

    public function handle(Guest $guest): void
    {
        $guest->update(
            [
                'name' => $guest->contact->name,
                'email' => $guest->contact->email,
                'phone' => $guest->contact->phone,


            ]
        );
    }

    public function asCommand(Command $command): void
    {
        $tenants = match ($command->option('tenant')) {
            [] => Tenant::all(),
            default => Tenant::where('nickname', $command->option('tenant'))->get()
        };

        $tenants->eachCurrent(function (Tenant $tenant) use ($command) {
            $command->info("Tenant: $tenant->nickname");

            if ($command->argument('guest_id') == 'all') {
                $this->loopAll($command);
            } else {
                $this->handle(Guest::findOrFail($command->argument('guest_id')));
                $command->info('Done!');
            }
        });
    }


    protected function loopAll(Command $command)
    {
        $command->withProgressBar(Guest::all(), function ($guest) {
            $this->handle($guest);
        });
        $command->info("");
    }

}



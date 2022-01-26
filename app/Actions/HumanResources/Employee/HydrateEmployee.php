<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 17 Jan 2022 18:59:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Models\Account\Tenant;
use App\Models\HumanResources\Employee;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;


class HydrateEmployee
{
    use AsAction;

    public string $commandSignature = 'hydrate:employee {employee_id} {--t|tenant=* : Tenant nickname}';

    public function handle(Employee $employee): void
    {
        $employee->update(
            [
                'name' => $employee->contact->name,
                'email' => $employee->contact->email,
                'phone' => $employee->contact->phone,


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

            if ($command->argument('employee_id') == 'all') {
                $this->loopAll($command);
            } else {
                $this->handle(Employee::findOrFail($command->argument('employee_id')));
                $command->info('Done!');
            }
        });
    }


    protected function loopAll(Command $command)
    {
        $command->withProgressBar(Employee::all(), function ($employee) {
            $this->handle($employee);
        });
        $command->info("");
    }

}



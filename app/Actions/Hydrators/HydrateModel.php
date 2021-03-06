<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Jan 2022 21:09:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\Account\Tenant;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;


class HydrateModel
{
    use AsAction;


    protected function getModel(int $id):?Model{
       return null;
    }
    #[Pure] protected function getAllModels():Collection{
       return new Collection();
    }


    public function asCommand(Command $command): void
    {
        $tenants = match ($command->option('tenant')) {
            [] => Tenant::all(),
            default => (new Tenant())->where('code', $command->option('tenant'))->get()
        };

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $tenants->eachCurrent(function (Tenant $tenant) use ($command) {

            $command->info("Tenant: $tenant->code");

            if ($command->argument('id') == 'all') {
                $this->loopAll($command);
            } else {
                $model=$this->getModel($command->argument('id'));
                if($model){
                    $this->handle($model);
                    $command->info('Done!');
                }

            }
        });
    }


    protected function loopAll(Command $command)
    {
        $command->withProgressBar($this->getAllModels(), function ($model) {
            if($model){
                $this->handle($model);
            }
        });
        $command->info("");
    }

}



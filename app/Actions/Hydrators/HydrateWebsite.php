<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 04:18:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Actions\Setup\SetupWebsite;
use App\Models\Account\Tenant;
use App\Models\Auth\User;
use App\Models\Auth\WebsiteUser;
use App\Models\Web\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;


class HydrateWebsite extends HydrateModel
{

    public string $commandSignature = 'hydrate:website {id} {--t|tenant=* : Tenant code} {--s|setup}';


    public function handle(Website $website): void
    {
        $this->webpagesStats($website);
        $this->syncUsers($website);
    }


    public function webpagesStats(Website $website)
    {
    }

    public function setup(Website $website)
    {
        SetupWebsite::run($website);
    }


    public function syncUsers(Website $website)
    {

        $currentUserIds=WebsiteUser::where('website_id',$website->id)->pluck('user_id')->all();

        $actualUserIds = [];
        /** @var User $user */


        foreach ((new User())->where('status', true)->where('tenant_id', app('currentTenant')->id)->get() as $user) {

            if ($user->hasPermissionTo("websites.view.$website->id")) {
                $actualUserIds[]=$user->id;
            }
        }

        foreach($actualUserIds as $userId){

                WebsiteUser::firstOrCreate(
                    [
                        'website_id'=>$website->id,
                        'user_id'=>$userId,
                    ]
                );

        }



        WebsiteUser::where('website_id',$website->id)->whereIn('user_id', array_diff($currentUserIds, $actualUserIds))->delete();


    }


    protected function getModel(int $id): Website
    {
        return Website::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Website::withTrashed()->get();
    }


    public function runCommand(Website $website, Command $command)
    {
        if ($command->option('setup')) {
            $command->info("Setting up website $website->url");
            $this->setup($website);
        } else {
            $this->handle($website);
        }
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
                $website = $this->getModel($command->argument('id'));
                $this->runCommand($website, $command);

                $command->info('Done!');
            }
        });
    }


    protected function loopAll(Command $command)
    {
        $command->withProgressBar($this->getAllModels(), function ($website) use ($command) {
            $this->runCommand($website, $command);
        });
        $command->info("");
    }


}



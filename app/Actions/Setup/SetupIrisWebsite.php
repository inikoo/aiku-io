<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 02 Apr 2022 02:50:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Setup;

use App\Models\Account\TenantWebsite;
use App\Models\Utils\ActionResult;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class SetupIrisWebsite
{
    use AsAction;

    public string $commandSignature = 'iris:setup {slug} {--rewrite}';

    public function handle(TenantWebsite $tenantWebsite, $rewrite = false): ActionResult
    {
        $res      = new ActionResult();
        $response = Http::acceptJson()
            ->withToken($tenantWebsite->iris_api_key)
            ->post(config('iris.url').'/setup',
                   [
                       'rewrite' => $rewrite
                   ]
            );
        switch ($response->status()) {
            case 401:
                $res->status   = 'error';
                $res->message  = 'Unauthenticated: Invalid token.';
                $res->errors[] = 'Unauthenticated';
                break;
            case 200:
                $res->status   = 'success';
                $res->message  = $response->json('message');
                break;
            default:
                $res->status   = 'error';
                $res->message  = $response->json('message');
                $res->errors[] = $response->json('code');
                break;


        }

        return $res;
    }

    public function asCommand(Command $command): void
    {
        $res = $this->handle(
            TenantWebsite::where('slug', $command->argument('slug'))->first(),
            $command->option('rewrite')
        );

        switch ($res->status) {
            case 'error':
                $command->error($res->message);
                break;
            default:
                $command->info($res->message);
                break;
        }
    }
}


<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 30 Mar 2022 20:46:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\WebsiteLayout;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Web\WebsiteLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;


/**
 * @property \App\Models\Web\WebsiteLayout $websiteLayout
 */
class PublishHeader
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;

    public function handle(WebsiteLayout $websiteLayout): ActionResult
    {
        $res = new ActionResult();

        $websiteLayout->header_published_id = $websiteLayout->header_preview_id;
        $websiteLayout->save();

        $res->changes  = $websiteLayout->getChanges();
        $res->model    = $websiteLayout;
        $res->model_id = $websiteLayout->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('websites:edit') || $request->user()->hasPermissionTo("websites.edit");
    }


    public function asController(WebsiteLayout $websiteLayout, ActionRequest $request): ActionResultResource
    {
        $request->validate();

        return new ActionResultResource(
            $this->handle(
                $websiteLayout
            )
        );
    }

    public function asInertia(WebsiteLayout $websiteLayout): RedirectResponse
    {
        $this->websiteLayout = $websiteLayout;
        $this->validateAttributes();
        $this->handle($websiteLayout);

        return Redirect::route('websites.webpage_layout.edit', $websiteLayout->id);
    }

}

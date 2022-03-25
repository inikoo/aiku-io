<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 26 Mar 2022 02:22:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\WebsiteLayout;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Web\WebpageLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;


/**
 * @property \App\Models\Web\WebpageLayout $websiteLayout
 */
class UpdateWebsiteLayout
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;

    public function handle(WebpageLayout $websiteLayout, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $websiteLayout->update($modelData);

        $res->changes = $websiteLayout->getChanges();
        $res->model = $websiteLayout;
        $res->model_id = $websiteLayout->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('websites:edit') || $request->user()->hasPermissionTo("websites.edit");
    }

    public function rules(): array
    {
        return [
            'footer_preview_id'                     => 'sometimes|required|exists:App\Models\Web\WebsiteComponent,id',
            'header_preview_id'                     => 'sometimes|required|exists:App\Models\Web\WebsiteComponent,id',

        ];
    }




    public function asController(WebpageLayout $websiteLayout, ActionRequest $request): ActionResultResource
    {
        $request->validate();

        $modelData = $request->only(
            'footer_preview_id',
            'header_preview_id',


        );


        return new ActionResultResource(
            $this->handle(
                $websiteLayout,
                $modelData
            )
        );
    }

    public function asInertia(WebpageLayout $websiteLayout, Request $request): RedirectResponse
    {
        $this->websiteLayout= $websiteLayout;
        $this->fillFromRequest($request);
        $this->validateAttributes();


        $modelData = $request->only(
            'footer_preview_id',
            'header_preview_id',

        );

        $this->handle(
            $websiteLayout,
            $modelData

        );

        return Redirect::route('websites.webpage_layout.edit', $websiteLayout->id);
    }

}

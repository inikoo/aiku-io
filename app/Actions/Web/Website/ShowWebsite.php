<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 14:35:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\Website;


use App\Actions\UI\WithInertia;
use App\Models\Web\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Website $website
 * @property string $module
 */
class ShowWebsite
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo( "websites.{$this->website->id}.view");
    }


    public function asInertia(Website $website, array $attributes = []): Response
    {
        $this->set('website', $website)->set('module', 'website')->fill($attributes);

        $this->validateAttributes();


        session(['currentWebsite' => $website->id]);


        return Inertia::render(
            'Common/ShowModel',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => $website->name,
                    'breadcrumbs' => $this->get('breadcrumbs'),

                ],
                'model'       => $website
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {

        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }


    private function breadcrumbs(): array
    {


        return array_merge(
            (new IndexWebsite())->getBreadcrumbs(),
            [
                'website' => [
                    'route'           => 'websites.show',
                    'routeParameters' => $this->website->id,
                    'name'            => $this->website->code,
                    'current'         => false
                ],
            ]
        );
    }


}

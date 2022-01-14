<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 14:31:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\Website;


use App\Models\Web\Website;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;
use function data_set;

/**
 * @property Website $website
 * @property string $module
 */
class WebsiteIndex
{
    use AsAction;
    use WithInertia;



    public function handle(): LengthAwarePaginator
    {

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('code', 'LIKE', "%$value%")
                    ->orWhere('name', 'LIKE', "%$value%")
                    ->orWhere('url', 'LIKE', "%$value%")
                ;
            });
        });

        return QueryBuilder::for(Website::class)
            ->allowedSorts(['code', 'name','url'])
            ->allowedFilters(['code','name','url', $globalSearch])
            ->paginate()
            ->withQueryString();
    }



    public function asInertia()
    {


        $this->set('module', 'websites');
        $this->validateAttributes();

        $breadcrumbs = $this->get('breadcrumbs');

        return Inertia::render(
            $this->get('page'),
            [
                'headerData' => [
                    'module'      => 'websites',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => data_set($breadcrumbs, "index.current", true),

                ],
                'websites'      => $this->handle(),


            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [

                ]
            );
        });
    }

    public function prepareForValidation(ActionRequest $request): void
    {


        $request->merge(
            [
                'page'  => 'Websites/Websites',
                'title' => __('Websites'),

            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs',$this->breadcrumbs());


    }


    private function breadcrumbs(): array
    {

        return [
            'index' => [
                'route'   => $this->module.'.index',
                'name'    => $this->get('title'),
                'current' => false
            ],
        ];
    }

    public function getBreadcrumbs($module): array
    {
        $this->set('module', $module);
        $this->validateAttributes();
        return $this->breadcrumbs();

    }


}

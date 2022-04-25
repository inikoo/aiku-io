<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 22 Jan 2022 16:20:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Profile;

use App\Actions\Assets\Language\IndexLanguage;
use App\Actions\UI\Localisation\GetUITranslations;
use App\Actions\UI\WithInertia;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property User $user
 * @property array $breadcrumbs
 */
class ShowEditProfile
{
    use AsAction;
    use WithInertia;

    public function handle()
    {
    }

    public function asInertia(Request $request, array $attributes = []): Response
    {
        $this->set('user', $request->user())->fill($attributes);
        $this->validateAttributes();

        $blueprint = [];

        $blueprint[] = [
            'title'    => __('Preferences'),
            'subtitle' => '',
            'fields'   => [

                'language_id' => [
                    'type'    => 'select',
                    'label'   => __('Language'),
                    'value'   => $this->user->language_id,
                    'options' => IndexLanguage::make()->asSelectOptions()
                ],
            ]
        ];

        return Inertia::render(
            'edit-model',
            [
                'translations' => GetUITranslations::run(),
                'language'     => App::currentLocale(),
                'headerData'   => [
                    'module'      => 'users',
                    'title'       => __('Editing profile'),
                    'breadcrumbs' => $this->breadcrumbs,

                    'actionIcons' => [
                        [
                            'route' => 'profile.show',
                            'name'  => __('Exit edit'),
                            'icon'  => ['fal', 'portal-exit']
                        ],
                    ],


                ],

                'formData' => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => "/profile",
                    ]

                ],
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
            (new ShowProfile())->getBreadcrumbs(),
            [
                'user' => [
                    'route'           => 'account.users.edit',
                    'routeParameters' => $this->user->id,
                    'name'            => $this->user->username,
                    'suffix'          => '('.__('editing').')',
                    'current'         => true
                ],

            ]
        );
    }


}

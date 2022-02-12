<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 12 Jan 2022 00:15:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\UI\Layout;

use App\Models\System\User;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserLayout
{
    use AsAction;

    protected User $user;


    public function handle(User $user, array $modulesScaffolding): array
    {
        $layout = [];

        $this->initialize($user);


        foreach ($modulesScaffolding as $moduleKey => $module) {
            $modulePermissions = $module['permissions'] ?? false;


            if ($this->canShow($module)) {
                /*

                $module = $this->prepareModule($module);





                $layout[$moduleKey] = array_merge(
                    Arr::except($module, ['sections', 'id']),
                    ['sections' => $sections]


                );
                */


                $sections = [];

                foreach ($this->getSections($module) as $sectionRoute => $section) {
                    $sectionPermissions = $section['permissions'] ?? false;

                    if (!$sectionPermissions or $user->hasAnyPermission($sectionPermissions)) {
                        $sections[$sectionRoute] = [
                            'icon'      => Arr::get($section, 'icon', ['fal', 'dot-circle']),
                            'name'      => __(Arr::get($section, 'name')),
                            'shortName' => __(Arr::get($section, 'shortName', Arr::get($section, 'name'))),
                        ];
                        if (Arr::get($section, 'metaSection')) {
                            $sections[$sectionRoute]['metaSection'] = Arr::get($section, 'metaSection');
                        }
                    }
                }


                $layout[] = [
                    'code'      => $module['code'],
                    'name'      => __($module['name']),
                    'shortName' => __($module['shortName']),
                    'route'     => $module['route'],
                    'sections'  => $sections
                ];
            }
        }


        return $layout;
    }

    protected function initialize($user)
    {
        $this->user = $user;
    }

    protected function prepareModule($module)
    {
        return $module;
    }

    protected function getSections($module)
    {
        return Arr::get($module, 'sections', []);
    }


    protected function canShow($moduleKey): bool
    {
        return true;
    }

}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 12 Jan 2022 00:15:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\UI\Layout;

use App\Models\Auth\User;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserLayout
{
    use AsAction;

    protected User $user;
    private array $modelsCount;
    private array $visibleModels;

    public function __construct()
    {
        $this->modelsCount = [];
        $this->visibleModels = [];

    }




    public function handle(User $user, array $modulesScaffolding): array
    {
        $this->initialize($user);
        $layout = [];


        foreach ($modulesScaffolding as $moduleKey => $module) {
            //$modulePermissions = $module['permissions'] ?? false;


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

                    if (Arr::get($section, 'model')) {
                        $sections[$sectionRoute]['model'] = Arr::get($section, 'model');
                    }else{

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
            }


            $moduleData     = [
                'code'      => $module['code'],
                'name'      => __($module['name']),
                'shortName' => __($module['shortName']),
                'route'     => $module['route'],
                'sections'  => $sections
            ];
            $visibleModels = $this->getVisibleModels($user, $module);
            if (!is_null($visibleModels)) {
                $moduleData['visibleModels']=$visibleModels;
                $moduleData['visibleModelsCount']=count($visibleModels);
                $moduleData['ModelsCount']=$this->getModelsCount($module['code']);


            }


            $layout[] = $moduleData;
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

    protected function getModelsCount($module): int
    {
        return 0;
    }

    protected function getVisibleModels($user, $module): ?array
    {
        return null;
    }


}

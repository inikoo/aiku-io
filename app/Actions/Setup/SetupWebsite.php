<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 04:31:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Setup;

use App\Actions\Web\Webpage\StoreWebpage;
use App\Actions\Web\WebsiteComponent\StoreWebsiteComponent;
use App\Actions\Web\WebsiteLayout\PublishFooter;
use App\Models\Web\Website;
use App\Models\Web\WebsiteComponent;
use App\Models\Web\WebsiteComponentBlueprint;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @property \App\Models\Web\Website $website
 */
class SetupWebsite
{
    use AsAction;

    public function handle(Website $website): void
    {
        $this->website = $website;
        $this->setupFooter();
        $this->setupHome();
        $this->setupEssentialWebpages();
    }

    public function setupFooter()
    {
        if (!$this->website->layout->footer_preview_id) {
            $footerComponent = WebsiteComponent::where('type', 'footer')->whereIn('status', ['published', 'preview', 'library'])
                ->orderByRaw("case status when 'published' then 1 when 'preview' then 2 when 'library' then 3 end") // <- valid only for postgres
                ->first();
            if (!$footerComponent) {
                $footerComponentBlueprint = WebsiteComponentBlueprint::where('type', 'footer')->first();
                $res                      = StoreWebsiteComponent::run($footerComponentBlueprint,
                                                                       [
                                                                           'website_id' => $this->website->id,
                                                                           'status'     => 'library',
                                                                       ]
                );
                $footerComponent          = $res->model;
            }

            $this->website->layout->footer_preview_id = $footerComponent->id;
            $this->website->layout->save();
        }


        if ($this->website->layout->state == 'launched') {
            PublishFooter::run($this->website->layout);
        }
    }


    public function getFooterComponent()
    {
    }

    public function setupHome($force = false)
    {
        if (!$this->website->layout->home_webpage_id or $force) {
            $res = StoreWebpage::run($this->website,
                                     [
                                         'name'   => 'home',
                                         'type'   => 'home',
                                         'locked' => true
                                     ]
            );

            $this->website->layout->home_webpage_id = $res->model_id;

            $this->website->layout->save();
        }
    }

    public function setupEssentialWebpages()
    {
        $essentialWebpages = [
            [
                'name' => 'about',
            ],
            [
                'name' => 'contact',
            ],
            [
                'name' => 'privacy',
            ],
            [
                'name' => 'terms-and_conditions',
            ]
        ];

        foreach ($essentialWebpages as $essentialWebpage) {
            StoreWebpage::run(
                $this->website,
                array_merge(
                    $essentialWebpage,
                    [
                        'type'   => 'info',
                        'locked' => false
                    ]
                )

            );
        }
    }


}

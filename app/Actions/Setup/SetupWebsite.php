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
use App\Actions\Web\WebsiteLayout\PublishHeader;
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
        $this->setupHeader();
        $this->setupFooter();
        $this->setupHome();
        $this->setupEssentialWebpages();
    }

    public function setupHeader()
    {
        if (!$this->website->currentLayout->header_preview_id) {
            $headerComponent = WebsiteComponent::where('type', 'header')->whereIn('status', ['published', 'preview', 'library'])
                ->orderByRaw("case status when 'published' then 1 when 'preview' then 2 when 'library' then 3 end") // <- valid only for postgres
                ->first();
            if (!$headerComponent) {
                $headerComponentBlueprint = WebsiteComponentBlueprint::where('type', 'header')->first();
                $res                      = StoreWebsiteComponent::run($headerComponentBlueprint,
                                                                       [
                                                                           'website_id' => $this->website->id,
                                                                           'status'     => 'library',
                                                                           'name'       => 'example'
                                                                       ]
                );
                $headerComponent          = $res->model;
            }

            $this->website->currentLayout->header_preview_id = $headerComponent->id;
            $headerComponent->status                  = 'preview';

            $this->website->currentLayout->save();
            $headerComponent->save();
        }


        if ($this->website->currentLayout->state == 'launched') {
            PublishHeader::run($this->website->layout);
        }
    }

    public function setupFooter()
    {
        if (!$this->website->currentLayout->footer_preview_id) {
            $footerComponent = WebsiteComponent::where('type', 'footer')->whereIn('status', ['published', 'preview', 'library'])
                ->orderByRaw("case status when 'published' then 1 when 'preview' then 2 when 'library' then 3 end") // <- valid only for postgres
                ->first();
            if (!$footerComponent) {
                $footerComponentBlueprint = WebsiteComponentBlueprint::where('type', 'footer')->first();
                $res                      = StoreWebsiteComponent::run($footerComponentBlueprint,
                                                                       [
                                                                           'website_id' => $this->website->id,
                                                                           'status'     => 'library',
                                                                           'name'       => 'example'
                                                                       ]
                );
                $footerComponent          = $res->model;
            }

            $this->website->currentLayout->footer_preview_id = $footerComponent->id;
            $footerComponent->status                  = 'preview';

            $this->website->currentLayout->save();
            $footerComponent->save();
        }


        if ($this->website->currentLayout->state == 'launched') {
            PublishFooter::run($this->website->layout);
        }
    }


    public function getFooterComponent()
    {
    }

    public function setupHome($force = false)
    {
        if (!$this->website->currentLayout->home_webpage_id or $force) {
            $res = StoreWebpage::run($this->website,
                                     [
                                         'name'   => 'home',
                                         'type'   => 'home',
                                         'locked' => true
                                     ]
            );

            $this->website->currentLayout->home_webpage_id = $res->model_id;

            $this->website->currentLayout->save();
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

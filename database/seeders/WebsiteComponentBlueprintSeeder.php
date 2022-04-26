<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 00:32:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Web\WebsiteComponentBlueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class WebsiteComponentBlueprintSeeder extends Seeder
{

    public function run()
    {
        $components = [

            [
                'type'      => 'header',
                'template'  => 'simple',
                'name'      => 'simple',
                'arguments' => [
                    'columns' => [
                        'solutions' => [

                            'template'  => 'popover',
                            'arguments' => [
                                'name'          => 'Solutions',
                                'items'         => [
                                    [
                                        'name'        => 'Analytics',
                                        'description' => 'Get a better understanding of where your traffic is coming from.',
                                        'href'        => '#',
                                        'icon'        => 'ChartBarIcon'
                                    ],
                                    [
                                        'name'        => 'Engagement',
                                        'description' => 'Speak directly to your customers in a more meaningful way.',
                                        'href'        => '#',
                                        'icon'        => 'CursorClickIcon'
                                    ],
                                    [
                                        'name'        => 'Security',
                                        'description' => "Your customers' data will be safe and secure.",
                                        'href'        => '#',
                                        'icon'        => 'ShieldCheckIcon'
                                    ],
                                    [
                                        'name'        => 'Integrations',
                                        'description' => "Connect with third-party tools that you're already using.",
                                        'href'        => '#',
                                        'icon'        => 'ViewGridIcon'
                                    ],
                                    [
                                        'name'        => 'Automations',
                                        'description' => "Build strategic funnels that will drive your customers to convert",
                                        'href'        => '#',
                                        'icon'        => 'RefreshIcon'
                                    ]
                                ],
                                'callsToAction' => [
                                    [
                                        'name' => 'Watch Demo',
                                        'href' => '#',
                                        'icon' => 'PlayIcon'
                                    ],
                                    [
                                        'name' => 'Contact Sales',
                                        'href' => '#',
                                        'icon' => 'PhoneIcon'
                                    ]
                                ]
                            ]
                        ],
                        'pricing'   => [

                            'template'  => 'link',
                            'arguments' => [
                                'name' => 'Pricing',
                                'href' => '#'
                            ]
                        ],
                        'docs'      => [

                            'template'  => 'link',
                            'arguments' => [
                                'name' => 'Docs',
                                'href' => '#'
                            ]
                        ],
                        'solutions' => [

                            'template'  => 'popover',
                            'arguments' => [
                                'name'        => 'Solutions',
                                'items'       => [
                                    [
                                        'name'        => 'Help Center',
                                        'description' => 'Get a better understanding of where your traffic is coming from.',
                                        'href'        => '#',
                                        'icon'        => 'SupportIcon'
                                    ],
                                    [
                                        'name'        => 'Guides',
                                        'description' => 'Learn how to maximize our platform to get the most out of it.',
                                        'href'        => '#',
                                        'icon'        => 'BookmarkAltIcon'
                                    ],
                                    [
                                        'name'        => 'Events',
                                        'description' => 'See what meet-ups and other events we might be planning near you.',
                                        'href'        => '#',
                                        'icon'        => 'CalendarIcon'
                                    ],
                                    [
                                        'name'        => 'Security',
                                        'description' => "Understand how we take your privacy seriously.",
                                        'href'        => '#',
                                        'icon'        => 'ShieldCheckIcon'
                                    ],

                                ],
                                'recentItems' => [
                                    'view_all'=>[
                                        'name' => 'View all posts',
                                        'href' => '#'
                                    ],
                                    'items' => [
                                        [
                                            'name' => 'Boost your conversion rate',
                                            'href' => '#'
                                        ],
                                        [
                                            'name' => 'How to use search engine optimization to drive traffic to your site',
                                            'href' => '#'
                                        ],
                                        [
                                            'name' => 'Improve your customer experience',
                                            'href' => '#'
                                        ],
                                    ],

                                ]
                            ]
                        ],
                    ]
                ]

            ],

            [
                'type'     => 'footer',
                'template' => 'social-links-only',
                'name'     => 'social links only',

            ],
            [
                'type'      => 'footer',
                'template'  => 'simple-centered',
                'name'      => 'simple centered',
                'settings'  => [
                    'social'    => 'inherit',
                    'copyright' => 'inherit',
                ],
                'arguments' => [
                    'navigation' => [
                        'main'   => [
                            [
                                'name' => 'About',
                                'href' => '#'
                            ],
                            [
                                'name' => 'Blog',
                                'href' => '#'
                            ],
                            [
                                'name' => 'Jobs',
                                'href' => '#'
                            ],
                            [
                                'name' => 'Press',
                                'href' => '#'
                            ],
                            [
                                'name' => 'Accessibility',
                                'href' => '#'
                            ],
                            [
                                'name' => 'Partners',
                                'href' => '#'
                            ],

                        ],
                        'social' => []
                    ],
                    'copyright'  => '&copy; 2020 Aiku, Inc. All rights reserved.'

                ]
            ],
        ];

        foreach ($components as $component) {
            WebsiteComponentBlueprint::upsert(
                [
                    [
                        'type'     => $component['type'],
                        'template' => $component['template'],
                        'name'     => $component['name'],

                        'sample_arguments' => json_encode(Arr::get($component, 'arguments', [])),
                        'settings'         => json_encode(Arr::get($component, 'settings', [])),

                    ],
                ],
                ['type', 'template', 'name'],
            );
        }
    }
}

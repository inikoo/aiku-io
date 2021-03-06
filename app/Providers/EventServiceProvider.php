<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 27 Dec 2021 15:02:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Providers;

use App\Events\CommonAttachmentAnchoring;
use App\Events\CommunalImageAnchoring;
use App\Listeners\UpdateCommonAttachmentStats;
use App\Listeners\UpdateCommunalImageStats;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AttachmentDeleted::class => [
            UpdateCommonAttachmentStats::class
        ],
        CommonAttachmentAnchoring::class => [
            UpdateCommonAttachmentStats::class
        ],
        CommunalImageAnchoring::class => [
            UpdateCommunalImageStats::class
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],



    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }
}

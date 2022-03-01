<?php

namespace App\Console;

use App\Actions\Hydrators\HydrateCustomer;
use App\Actions\Hydrators\HydrateEmployee;
use App\Actions\Hydrators\HydrateFulfilmentCustomer;
use App\Actions\Hydrators\HydrateGuest;
use App\Actions\Hydrators\HydrateShop;
use App\Actions\Hydrators\HydrateTenant;
use App\Actions\Hydrators\HydrateUser;
use App\Actions\Hydrators\HydrateWarehouse;
use App\Actions\Hydrators\HydrateWarehouseArea;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        HydrateUser::class,
        HydrateEmployee::class,
        HydrateGuest::class,
        HydrateWarehouse::class,
        HydrateWarehouseArea::class,
        HydrateShop::class,
        HydrateCustomer::class,
        HydrateTenant::class,
        HydrateFulfilmentCustomer::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

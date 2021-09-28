<?php

namespace App\Console;

use App\Console\Commands\AuroraMigration\MigrateCustomers;
use App\Console\Commands\AuroraMigration\MigrateHR;
use App\Console\Commands\Account\CreateAccountAdmin;
use App\Console\Commands\Account\CreateAdminAccessToken;
use App\Console\Commands\TenantsAdmin\CreateTenant;
use App\Console\Commands\TenantsAdmin\CreateTenantAccessToken;
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
        CreateTenantAccessToken::class,
        CreateTenant::class,
        CreateAccountAdmin::class,
        CreateAdminAccessToken::class,
        MigrateHR::class,
        MigrateCustomers::class
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

<?php

namespace App\Console;

use App\Console\Commands\AuroraMigration\EmployeesAurora;
use App\Console\Commands\Landlord\CreateAdmin;
use App\Console\Commands\Landlord\CreateAdminAccessToken;
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
        CreateAdmin::class,
        CreateAdminAccessToken::class,
        EmployeesAurora::class
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

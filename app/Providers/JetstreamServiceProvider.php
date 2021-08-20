<?php

namespace App\Providers;


use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();
        Jetstream::deleteUsersUsing(DeleteUser::class);

        Collection::macro('toLocale', function ($locale) {
            return $this->map(function ($item) use ($locale) {
                $item['name']=Lang::get($item['name']);



                return $item ;

            });
        });

    }

    /**
     * Configure the roles and permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
                                   'create',
                                   'read',
                                   'update',
                                   'delete',

                               ]);

       }
}

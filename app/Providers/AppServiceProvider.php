<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        Schema::defaultStringLength(125);

        Relation::morphMap(
            [
                'Contact' => 'App\Models\Helpers\Contact',
                'Address' => 'App\Models\Helpers\Address',
                'Tenant'  => 'App\Models\Aiku\Tenant',
                'User'    => 'App\Models\System\User',
                'Patient' => 'App\Models\Health\Patient',
                /*
                'Admin'                    => 'App\Models\System\Admin',
                'Guest'                    => 'App\Models\System\Guest',
                'Employee'                 => 'App\Models\HR\Employee',
                'Customer'                 => 'App\Models\CRM\Customer',
                'CustomerClient'           => 'App\Models\CRM\CustomerClient',
                'Prospect'                 => 'App\Models\CRM\Prospect',
                'WebBlock'                 => 'App\Models\ECommerce\WebBlock',
                'Order'                    => 'App\Models\Sales\Order',
                'Basket'                   => 'App\Models\Sales\Basket',
                'ShippingZone'             => 'App\Models\Sales\ShippingZone',
                'Charge'                   => 'App\Models\Sales\Charge',
                'Invoice'                  => 'App\Models\Sales\Invoice',
                'DeliveryNote'             => 'App\Models\Distribution\DeliveryNote',
                'Stock'                    => 'App\Models\Distribution\Stock',
                'Store'                    => 'App\Models\Stores\Store',
                'Product'                  => 'App\Models\Stores\Product',
                'ProductHistoricVariation' => 'App\Models\Stores\ProductHistoricVariation',
                'EmailService'             => 'App\Models\Notifications\EmailService',
                'Mailshot'                 => 'App\Models\Notifications\Mailshot',
                'Category'                 => 'App\Models\Helpers\Category',
                'OriginalImage'            => 'App\Models\Helpers\OriginalImage',
                'ProcessedImage'           => 'App\Models\Helpers\ProcessedImage',
                'User'                     => 'App\User',
                'Tenant'                   => 'App\Tenant',
                */
            ]
        );
    }
}

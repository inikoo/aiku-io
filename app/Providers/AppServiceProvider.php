<?php

namespace App\Providers;

use Closure;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

/**
 * @method map(Closure $param)
 */
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
        //Schema::defaultStringLength(191); for Mysql only

        Relation::morphMap(
            [
                'AccountUser'    => 'App\Models\Account\AccountUser',
                'AccountAdmin'   => 'App\Models\Account\AccountAdmin',
                'Contact'        => 'App\Models\Helpers\Contact',
                'Address'        => 'App\Models\Helpers\Address',
                'Tenant'         => 'App\Models\Account\Tenant',
                'User'           => 'App\Models\System\User',
                'Patient'        => 'App\Models\Health\Patient',
                'Employee'       => 'App\Models\HumanResources\Employee',
                'Guest'          => 'App\Models\System\Guest',
                'Customer'       => 'App\Models\CRM\Customer',
                'RawImage'       => 'App\Models\Assets\RawImage',
                'ProcessedImage' => 'App\Models\Assets\ProcessedImage',
                'Product'        => 'App\Models\Trade\Product',
                'Agent'          => 'App\Models\Buying\Agent',
                'Supplier'       => 'App\Models\Buying\Supplier',
                'Shop'           => 'App\Models\Trade\Shop',
                'Adjust'         => 'App\Models\Sales\Adjust',
                'Shipper'        => 'App\Models\Delivery\Shipper',
                'DeliveryNote'   => 'App\Models\Delivery\DeliveryNote',
                'Invoice'        => 'App\Models\Accounting\Invoice',
                'Order'          => 'App\Models\Sales\Order',
                'Stock'          => 'App\Models\Inventory\Stock',


                /*
                'AccountAdmin'                    => 'App\Models\System\AccountAdmin',

                'CustomerClient'           => 'App\Models\CRM\CustomerClient',
                'Prospect'                 => 'App\Models\CRM\Prospect',
                'WebBlock'                 => 'App\Models\ECommerce\WebBlock',

                'ShippingZone'             => 'App\Models\Sales\ShippingZone',
                'Charge'                   => 'App\Models\Sales\Charge',
                'Product'                  => 'App\Models\Stores\Product',
                'ProductHistoricVariation' => 'App\Models\Stores\ProductHistoricVariation',
                'EmailService'             => 'App\Models\Notifications\EmailService',
                'Mailshot'                 => 'App\Models\Notifications\Mailshot',
                'Category'                 => 'App\Models\Helpers\Category',

                'AccountUser'                     => 'App\AccountUser',
                'Tenant'                   => 'App\Tenant',
                */
            ]
        );

        Collection::macro('toLocale', function ($locale) {
            return $this->map(function ($item) use ($locale) {
                //$item['name']=Lang::get($item['name']);


                return $item;
            });
        });
    }
}

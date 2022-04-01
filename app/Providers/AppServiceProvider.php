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
                'AccountAdmin'    => 'App\Models\Admin\AccountAdmin',
                'Tenant'          => 'App\Models\Account\Tenant',
                'User'            => 'App\Models\Auth\User',
                'AdminUser'       => 'App\Models\Admin\AdminUser',
                'Address'         => 'App\Models\Helpers\Address',
                'Patient'         => 'App\Models\Health\Patient',
                'Employee'        => 'App\Models\HumanResources\Employee',
                'Guest'           => 'App\Models\HumanResources\Guest',
                'Customer'        => 'App\Models\CRM\Customer',
                'RawImage'        => 'App\Models\Media\RawImage',
                'ProcessedImage'  => 'App\Models\Assets\ProcessedImage',
                'Department'      => 'App\Models\Marketing\Department',
                'Family'          => 'App\Models\Marketing\Family',
                'Product'         => 'App\Models\Marketing\Product',
                'HistoricProduct' => 'App\Models\Marketing\HistoricProduct',

                'TenantWebsite' => 'App\Models\Account\TenantWebsite',
                'Website'       => 'App\Models\Web\Website',
                'WebsiteUser'   => 'App\Models\Auth\WebsiteUser',


                'WorkshopProduct'         => 'App\Models\Production\WorkshopProduct',
                'HistoricWorkshopProduct' => 'App\Models\Production\HistoricWorkshopProduct',

                'Agent'                   => 'App\Models\Procurement\Agent',
                'Supplier'                => 'App\Models\Procurement\Supplier',
                'SupplierProduct'         => 'App\Models\Procurement\SupplierProduct',
                'HistoricSupplierProduct' => 'App\Models\Procurement\HistoricSupplierProduct',
                'PurchaseOrder'           => 'App\Models\Procurement\PurchaseOrder',
                'ProcurementDelivery'     => 'App\Models\Procurement\ProcurementDelivery',


                'Shop'         => 'App\Models\Marketing\Shop',
                'Adjust'       => 'App\Models\Sales\Adjust',
                'Shipper'      => 'App\Models\Delivery\Shipper',
                'DeliveryNote' => 'App\Models\Delivery\DeliveryNote',
                'Invoice'      => 'App\Models\Financials\Invoice',
                'Order'        => 'App\Models\Sales\Order',
                'Stock'        => 'App\Models\Inventory\Stock',
                'Workshop'     => 'App\Models\Production\Workshop',


                /*
                'AccountAdmin'                    => 'App\Models\Tenant\AccountAdmin',

                'CustomerClient'           => 'App\Models\CRM\CustomerClient',
                'Prospect'                 => 'App\Models\CRM\Prospect',
                'WebBlock'                 => 'App\Models\ECommerce\WebBlock',

                'ShippingZone'             => 'App\Models\Sales\ShippingZone',
                'Charge'                   => 'App\Models\Sales\Charge',
                'ProductHistoricVariation' => 'App\Models\Stores\ProductHistoricVariation',
                'EmailService'             => 'App\Models\Notifications\EmailService',
                'Mailshot'                 => 'App\Models\Notifications\Mailshot',
                'Category'                 => 'App\Models\Helpers\Category',

                */
            ]
        );

        //todo Make the actual translation
        Collection::macro('translateElement', function ($index, $locale) {
            return $this->map(function ($item) use ($index, $locale) {
                //$item['name']=Lang::get($item[$index]);


                return $item;
            });
        });
    }
}

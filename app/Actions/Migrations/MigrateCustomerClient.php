<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 13 Oct 2021 22:48:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\CRM\Customer\StoreCustomer;

use App\Models\CRM\Customer;
use App\Models\Trade\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateCustomerClient extends MigrateModel
{
    use WithCustomer;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Customer Client Dimension';
        $this->auModel->id_field = 'Customer Client Key';
    }

    /** @noinspection PhpUnusedParameterInspection */
    private function parseContactMetadata($auData, array $metadataContact = []): array
    {
        return [];
    }

    public function parseModelData()
    {
        $status = 'approved';

        if ($this->auModel->data->{'Customer Client Status'} == 'Active') {
            $state      = 'active';
            $deleted_at = null;
        } else {
            $state      = 'lost';
            $metadata   = json_decode($this->auModel->data->{'Customer Client Metadata'} ?? '{}');
            $deleted_at = $metadata->deactivated_date;
        }

        $this->modelData['contact'] = $this->sanitizeData(
            [
                'name'       => $this->auModel->data->{'Customer Client Main Contact Name'},
                'company'    => $this->auModel->data->{'Customer Client Company Name'},
                'email'      => $this->auModel->data->{'Customer Client Main Plain Email'},
                'phone'      => $this->auModel->data->{'Customer Client Main Plain Mobile'},
                'created_at' => $this->auModel->data->{'Customer Client Creation Date'},

            ]
        );

        $this->modelData['customer'] = $this->sanitizeData(
            [
                'name'                      => $this->auModel->data->{'Customer Client Code'},
                'state'                     => $state,
                'status'                    => $status,
                'aurora_customer_client_id' => $this->auModel->data->{'Customer Client Key'},
                'created_at' => $this->auModel->data->{'Customer Client Creation Date'},
                'deleted_at' => $deleted_at,
            ]
        );


        $addresses = [];

        $deliveryAddress = $this->parseAddress(prefix: 'Customer Client Contact', auAddressData: $this->auModel->data);

        $addresses['delivery'] = [
            $deliveryAddress
        ];

        $this->modelData['addresses'] = $addresses;


        $this->auModel->id = $this->auModel->data->{'Customer Client Key'};
    }

    public function getParent(): Model|Shop|Builder|\Illuminate\Database\Query\Builder|null
    {
        return Customer::withTrashed()->firstWhere('aurora_customer_id', $this->auModel->data->{'Customer Client Customer Key'});
    }

    public function setModel()
    {
        $this->model = Customer::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel():MigrationResult
    {
       return  $this->updateCustomer($this->auModel->data);
    }

    public function storeModel(): MigrationResult
    {
        return StoreCustomer::run(
            vendor:                $this->parent,
            customerData:          $this->modelData['customer'],
            contactData:           $this->modelData['contact'],
            customerAddressesData: $this->modelData['addresses']
        );

    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }


    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Customer Client Dimension')->where('Customer Client Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res  = new MigrationResult();
        $res->errors[]='Aurora model not found';
        $res->status='error';
        return $res;
    }


}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Oct 2021 15:25:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\System\User\CreateUserToken;
use App\Models\Buying\Agent;
use App\Models\Buying\Supplier;
use App\Models\HumanResources\Employee;
use App\Models\Selling\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;


class MigrateUser extends MigrateModel
{
    use WithUser;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'User Dimension';
        $this->auModel->id_field = 'User Key';
    }

    public function getParent(): Employee|Supplier|Agent|null
    {

        return match ($this->auModel->data->{'User Type'}) {
            'Staff', 'Contractor' => Employee::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'User Parent Key'}),
            'Supplier' => Supplier::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'User Parent Key'}),
            'Agent' => Agent::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'User Parent Key'}),

            default => null
        };
    }

    public function parseModelData()
    {
        $roles = [];
        foreach (DB::connection('aurora')->table('User Group User Bridge')->where('User Key', $this->auModel->data->{'User Key'})->select('User Group Key')->get() as $auRole) {
            foreach (
            match ($auRole->{'User Group Key'}) {
                1 => ['system-admin'],
                6 => ['human-resources-clerk'],
                20 => ['human-resources-admin'],
                8 => ['buyer-clerk'],
                21, 28 => ['buyer-admin'],
                4 => ['production-operative'],
                27 => ['production-admin'],
                3 => ['distribution-clerk'],
                22 => ['distribution-admin'],
                23 => ['accounts-admin'],
                17 => ['distribution-dispatcher'],
                24, 25 => ['distribution-operative'],
                16 => $this->getShopScopedRoles($this->auModel->data->{'User Key'}, 'customer-services-#-admin'),
                2 => $this->getShopScopedRoles($this->auModel->data->{'User Key'}, 'customer-services-#-clerk'),

                default => [],
            } as $role
            ) {
                array_push($roles, $role);
            }
        }


        $this->modelData['roles'] = $roles;

        $this->modelData['user'] = $this->sanitizeData(
            [
                'username'    => strtolower($this->auModel->data->{'User Handle'}),
                'password'    => Hash::make(config('app.env') == 'local' ? 'hello' : wordwrap(Str::random(), 4, '-', true)),
                'aurora_id'   => $this->auModel->data->{'User Key'},
                'language_id' => $this->parseLanguageID($this->auModel->data->{'User Preferred Locale'}),
                'status'      => $this->auModel->data->{'User Active'} == 'Yes' ? 1 : 0,
                'created_at'  => $this->auModel->data->{'User Created'},
            ]
        );

        $this->auModel->id = $this->auModel->data->{'User Key'};
    }

    private function getShopScopedRoles($auUserKey, $role): array
    {
        $activeShops     = Shop::where('state', '!=', 'closed')->get()->pluck('aurora_id');
        $authorisedShops = DB::connection('aurora')->table('User Right Scope Bridge')->where('User Key', $auUserKey)->where('Scope', 'Store')->pluck('Scope Key');

        $diff = $activeShops->diff($authorisedShops);


        if (count($diff)) {
            $roles = [];
            foreach ($authorisedShops as $aurora_id) {
                /** @var Shop $shop */
                $shop    = Shop::where('aurora_id', $aurora_id)->first();
                $roles[] = preg_replace('/#/', $shop->id, $role);
            }

            return $roles;
        } else {
            return [preg_replace('/#/', '*', $role)];
        }
    }

    protected function postMigrateActions()
    {
        $token = CreateUserToken::run($this->model);
        DB::connection('aurora')->table($this->auModel->table)
            ->where($this->auModel->id_field, $this->auModel->id)
            ->update(['aiku_token' => $token]);
    }

}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 14 Oct 2021 17:55:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;



use App\Models\Buying\Agent;
use App\Models\Buying\Supplier;
use App\Models\HumanResources\Employee;
use http\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;


class MigrateDeletedUser extends MigrateModel
{
    use WithUser;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'User Deleted Dimension';
        $this->auModel->id_field = 'User Deleted Key';
    }

    public function getParent(): Employee|Supplier|Agent|null
    {
        $auDeletedModel = json_decode(gzuncompress($this->auModel->data->{'User Deleted Metadata'}));

        return match ($this->auModel->data->{'User Deleted Type'}) {
            'Staff','Contractor' => Employee::withTrashed()->firstWhere('aurora_id', $auDeletedModel->data->{'User Parent Key'}),
            'Supplier' => Supplier::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'User Parent Key'}),
            'Agent' => Agent::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'User Parent Key'}),
            default => null
        };
    }

    public function parseModelData()
    {

        $auDeletedModel = json_decode(gzuncompress($this->auModel->data->{'User Deleted Metadata'}));

        $this->modelData['user'] = $this->sanitizeData(
            [
                'username'    => null,
                'password'    => Hash::make(config('app.env') == 'local' ? 'hello' : wordwrap(Str::random(), 4, '-', true)),
                'aurora_id'   => $this->auModel->data->{'User Deleted Key'},
                'language_id' => $this->parseLanguageID($auDeletedModel->data->{'User Preferred Locale'}),
                'status'      => 0,
                'created_at'=>$auDeletedModel->data->{'User Created'},
                'deleted_at'=>$this->auModel->data->{'User Deleted Date'},
                'data'=>[
                    'username'=>strtolower($this->auModel->data->{'User Deleted Handle'})
                ]
            ]
        );
        $this->modelData['roles']=[];

        $this->auModel->id = $this->auModel->data->{'User Deleted Key'};
    }



}

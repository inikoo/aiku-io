<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Oct 2021 15:25:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Models\HumanResources\Employee;
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

    public function getParent(): Employee|null
    {
        return match ($this->auModel->data->{'User Type'}) {
            'Staff', 'Contractor' => Employee::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'User Parent Key'}),
            default => null
        };
    }

    public function parseModelData()
    {
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


}

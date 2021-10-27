<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 23 Oct 2021 18:52:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use Illuminate\Database\Eloquent\Model;

class MigrationResult
{
    public array $changes;
    public array $errors;
    public Model|null $model;
    public string $status;

    public int|null $model_id;

    public function __construct()
    {
        $this->changes=[];
        $this->errors=[];

        $this->status='unchanged';

        $this->model=null;
        $this->model_id=null;

    }
}

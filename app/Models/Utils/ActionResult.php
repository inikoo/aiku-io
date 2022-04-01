<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 19:54:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Utils;

use Illuminate\Database\Eloquent\Model;

class ActionResult
{
    public array $changes;
    public array $errors;
    public Model|null $model;
    public string $status;

    public int|null $model_id;
    public array $data;
    public string $message;

    public function __construct()
    {
        $this->changes = [];
        $this->errors  = [];
        $this->message = '';

        $this->status = 'unchanged';

        $this->model    = null;
        $this->model_id = null;
        $this->data     = [];
    }
}

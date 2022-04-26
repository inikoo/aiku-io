<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Apr 2022 17:02:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Staffing;

use App\Models\Traits\HasPersonalData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperApplicant
 */
class Applicant extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use Searchable;
    use HasFactory;
    use UsesTenantConnection;
    use HasPersonalData;

    protected $casts = [
        'data'          => 'array',
        'errors'        => 'array',
        'date_of_birth' => 'datetime:Y-m-d',

    ];

    protected $attributes = [
        'data'          => '{}',
        'errors'        => '{}',
    ];

    protected $guarded = [];


}

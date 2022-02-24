<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 18 Dec 2021 15:01:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\HumanResources;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperJobPosition
 */
class JobPosition extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $casts = [
        'data'  => 'array',
        'roles' => 'array',
    ];

    protected $attributes = [
        'data'  => '{}',
        'roles' => '{}',
    ];

    protected $guarded = [];

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class)->withTimestamps();
    }


}

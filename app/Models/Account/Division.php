<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 17:17:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Account;

use App\Actions\UI\Layout\GetUserLayout;
use App\Actions\UI\Layout\GetUserLayoutEcommerce;
use App\Models\System\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;


class Division extends Model
{
    use UsesLandlordConnection;

    protected $guarded = [];
    protected $attributes = [
        'data' => '{}',
    ];
    protected $casts = [
        'data' => 'array'
    ];

    public function tenants(): HasMany
    {
        return $this->hasMany('App\Models\Account\Tenant');
    }

    public function getLayout(): Array
    {
        return config('division.'.app('currentTenant')->division->slug.'.layout', []);
    }

    public function getUserLayout(?User $user): array
    {
        if (!$user) {
            return [];
        }

        return match ($this->slug) {
            'ecommerce' => GetUserLayoutEcommerce::run($user, $this->getLayout()),
            'health' => GetUserLayout::run($user, $this->getLayout()),
            default => [],
        };
    }

}

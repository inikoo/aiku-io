<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 22 Feb 2022 16:24:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Aiku;

use App\Actions\UI\Layout\GetUserLayoutAgent;
use App\Actions\UI\Layout\GetUserLayoutEcommerce;
use App\Models\Account\Tenant;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

use function config;



/**
 * @mixin IdeHelperAppType
 */
class AppType extends Model
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
        return $this->hasMany(Tenant::class);
    }

    public function aikuApp(): HasOne
    {
        return $this->hasOne(Aiku::class);
    }

    public function getLayout(): Array
    {
        return config('app_type.'.app('currentTenant')->appType->code.'.layout', []);
    }

    public function getUserLayout(?User $user): array
    {
        if (!$user) {
            return [];
        }

        return match ($this->code) {
            'ecommerce' => GetUserLayoutEcommerce::run($user, $this->getLayout()),
            'agent' => GetUserLayoutAgent::run($user, $this->getLayout()),
            'health' => GetUserLayoutHealth::run($user, $this->getLayout()),
            default => [],
        };
    }



}

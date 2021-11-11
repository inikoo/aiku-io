<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 10 Nov 2021 16:20:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Multitenancy\Tasks;


use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;
use Spatie\Permission\PermissionRegistrar;

class PrefixCacheTask implements SwitchTenantTask
{
    protected ?string $originalPrefix;

    public function __construct(
        protected ?string $storeName = null,
        protected ?string $cacheKeyBase = null
    ) {
        $this->originalPrefix = config('cache.original-prefix');

        $this->storeName ??= config('cache.default');

        $this->cacheKeyBase ??= 't_';
    }

    public function makeCurrent(Tenant $tenant): void
    {
        $this->setCachePrefix('_'.$this->cacheKeyBase.$tenant->id);
    }

    public function forgetCurrent(): void
    {
        $this->setCachePrefix('');
    }


    protected function setCachePrefix(string $prefix)
    {
        config()->set('cache.prefix', $this->originalPrefix.$prefix);
        app('cache')->forgetDriver($this->storeName);

        app()[PermissionRegistrar::class]->initializeCache();
    }

}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 00:33:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


namespace App\Models\Media;

use App\Models\Account\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

use function app;


/**
 * @mixin IdeHelperCommunalImage
 */
class CommunalImage extends Model {

    protected $connection= 'media';


    protected $guarded =[];

    public function imageable(): MorphTo {
        return $this->morphTo();
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)->using(CommunalImageTenant::class)->withTimestamps();
    }

    public function getRelationsCounts(): array
    {
        $currentTenant = App('currentTenant');

        $numberRelations        = 0;
        $numberDeletedRelations = 0;
        $tenants                = 0;

        foreach (
            DB::connection('media')
                ->table('communal_image_tenant')
                ->where('communal_image_id', $this->id)
                ->select('tenant_id')
                ->get() as $row
        ) {
            $tenants++;
            $tenant = Tenant::find($row->tenant_id);
            Tenant::current()->is($tenant);


            $numberRelations += DB::connection('tenant')
                ->table('images')
                ->where('communal_image_id', $this->id)
                ->whereNull('deleted_at')
                ->count();

            $numberDeletedRelations += DB::connection('tenant')
                ->table('images')
                ->where('communal_image_id', $this->id)
                ->whereNotNull('deleted_at')
                ->count();
        }
        Tenant::current()->is($currentTenant);

        return [
            'relations'         => $numberRelations,
            'deleted_relations' => $numberDeletedRelations,
            'tenants'           => $tenants
        ];
    }

}

<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 13:14:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Helpers;

use App\Models\Account\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use App\Models\Traits;


/**
 * @mixin IdeHelperCommonAttachment
 */
class CommonAttachment extends Model
{
    use SoftDeletes;
    use Traits\HasFile;

    protected $casts = [
        'relations' => 'array'
    ];

    protected $attributes = [
        'relations' => '[]',
    ];

    protected $connection = 'media';

    protected $guarded = [];


    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)->using(CommonAttachmentTenant::class)->withTimestamps();
    }

    /** @noinspection PhpUnused */
    #[Pure] public function getFormattedFilesizeAttribute(): string
    {
        return $this->formatSizeUnits($this->filesize);
    }

    public function getRelationsCounts(): array
    {
        $currentTenant = App('currentTenant');

        $numberRelations        = 0;
        $numberDeletedRelations = 0;
        $tenants                = 0;

        foreach (
            DB::connection('media')
                ->table('common_attachment_tenant')
                ->where('common_attachment_id', $this->id)
                ->select('tenant_id')
                ->get() as $row
        ) {
            $tenants++;
            $tenant = Tenant::find($row->tenant_id);
            Tenant::current()->is($tenant);


            $numberRelations += DB::connection('tenant')
                ->table('attachments')
                ->where('common_attachment_id', $this->id)
                ->whereNull('deleted_at')
                ->count();

            $numberDeletedRelations += DB::connection('tenant')
                ->table('attachments')
                ->where('common_attachment_id', $this->id)
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

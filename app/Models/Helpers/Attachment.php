<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 13:14:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperAttachment
 */
class Attachment extends Model {
    use UsesTenantConnection;
    use SoftDeletes;


    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function models(): HasMany {
        return $this->hasMany(AttachmentModel::class);
    }




}

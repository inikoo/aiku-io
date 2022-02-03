<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 11 Oct 2021 19:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Procurement;

use App\Actions\Hydrators\HydrateAgent;
use App\Models\Helpers\Attachment;
use App\Models\Media\Image;
use App\Models\System\User;
use App\Models\Trade\Product;
use App\Models\Traits\HasAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperSupplier
 */
class Supplier extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use Searchable;
    use HasAddress;

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
        'location' => 'array',
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
        'location' => '{}',
    ];

    protected static function booted()
    {
        static::created(
            function (Supplier $supplier) {
                if ($supplier->owner_type == 'Agent') {
                    HydrateAgent::run($supplier->owner);
                }
            }
        );
        static::deleted(
            function (Supplier $supplier) {
                if ($supplier->owner_type == 'Agent') {
                    HydrateAgent::run($supplier->owner);
                }
            }
        );
    }


    protected $guarded = [];


    public function addresses(): MorphToMany
    {
        return $this->morphToMany('App\Models\Helpers\Address', 'addressable')->withTimestamps();
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'image_model', 'imageable_type', 'imageable_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachment_model', 'attachmentable_type', 'attachmentable_id');
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'vendor');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function purchaseOrders(): MorphMany
    {
        return $this->morphMany(PurchaseOrder::class, 'vendor');
    }

    public function stats(): HasOne
    {
        return $this->hasOne(SupplierStats::class);
    }
}

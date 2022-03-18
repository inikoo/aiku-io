<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 27 Oct 2021 21:35:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Procurement;

use App\Actions\Hydrators\HydrateAgent;
use App\Actions\Hydrators\HydrateSupplier;
use App\Actions\Hydrators\HydrateTenant;
use App\Models\Helpers\Attachment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperPurchaseOrder
 */
class PurchaseOrder extends Model implements Auditable
{
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'data' => 'array',
        'submitted_at' => 'datetime'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function (PurchaseOrder $purchaseOrder) {

                if ($purchaseOrder->vendor_type == 'Supplier') {
                    HydrateSupplier::make()->stats($purchaseOrder->vendor);
                }elseif ($purchaseOrder->vendor_type == 'Agent') {
                    HydrateAgent::make()->stats($purchaseOrder->vendor);
                }
                HydrateTenant::make()->purchaseOrdersStats();

            }
        );
        static::deleted(
            function (PurchaseOrder $purchaseOrder) {
                if ($purchaseOrder->vendor_type == 'Supplier') {
                    HydrateSupplier::make()->stats($purchaseOrder->vendor);
                }elseif ($purchaseOrder->vendor_type == 'Agent') {
                    HydrateAgent::make()->stats($purchaseOrder->vendor);
                }
                HydrateTenant::make()->purchaseOrdersStats();

            }
        );
    }

    public function vendor(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachment_model', 'attachmentable_type', 'attachmentable_id');
    }
}

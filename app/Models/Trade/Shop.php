<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 15:17:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Trade;

use App\Models\CRM\Customer;
use App\Models\Helpers\Contact;
use App\Models\Trade\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @mixin IdeHelperShop
 */
class Shop extends Model implements Auditable
{
    use HasSlug;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array'
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('code')
            ->doNotGenerateSlugsOnCreate()
            ->doNotGenerateSlugsOnUpdate();
    }

    public function contact(): MorphOne
    {
        return $this->morphOne(Contact::class, 'contactable');
    }

    public function customers(): MorphMany
    {
        return $this->morphMany(Customer::class, 'vendor');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'vendor');
    }


}

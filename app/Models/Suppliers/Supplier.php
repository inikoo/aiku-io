<?php

namespace App\Models\Suppliers;

use App\Models\Helpers\Contact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    protected $casts = [
        'data' => 'array',
        'settings' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
        'settings' => '{}',
    ];

    protected $guarded = [];

    public function contact(): MorphOne
    {
        return $this->morphOne(Contact::class, 'contactable');
    }

    public function addresses(): MorphToMany
    {
        return $this->morphToMany('App\Models\Helpers\Address', 'addressable')->withTimestamps();
    }

    public function images(): MorphMany
    {
        return $this->morphMany('App\Models\Helpers\ImageModel', 'image_models', 'imageable_type', 'imageable_id');
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}

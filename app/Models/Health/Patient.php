<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 22:14:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Health;

use App\Models\Helpers\Contact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperPatient
 */
class Patient extends Model
{
    use UsesTenantConnection;
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'data'     => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class)->withPivot('relation')->withTimestamps();
    }
}

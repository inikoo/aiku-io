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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @mixin IdeHelperPatient
 */
class Patient extends Model implements Auditable
{
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $appends = [ 'formatted_id','formatted_dob','age'];

    protected $fillable = [
        'type'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class)->withPivot('relation')->withTimestamps();
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class)->withPivot('relation')->withTimestamps();
    }

    /** @noinspection PhpUnused */
    public function getFormattedIdAttribute(): string
    {
        return sprintf('%04d', $this->getAttribute('id'));
    }

    /** @noinspection PhpUnused */
    public function getFormattedDobAttribute(): string
    {
        return $this->contact ? $this->contact->formatted_dob : __('Contact missing');
    }
    /** @noinspection PhpUnused */
    public function getAgeAttribute(): string
    {
        return $this->contact ? $this->contact->age : __('Contact missing');

    }

}

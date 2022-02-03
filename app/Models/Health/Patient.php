<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 22:14:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Health;

use App\Models\Traits\HasPersonalData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    use HasPersonalData;

    protected $appends = [ 'formatted_id','formatted_dob','age'];

    protected $guarded = [];

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];




    public function getFormattedIdAttribute(): string
    {
        return sprintf('%04d', $this->getAttribute('id'));
    }


    public function getFormattedDobAttribute(): string
    {
        return $this->contact ? $this->contact->formatted_dob : __('Contact missing');
    }

    public function getAgeAttribute(): string
    {
        return $this->contact ? $this->contact->age : __('Contact missing');

    }

}

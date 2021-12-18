<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 13 Sep 2021 18:02:59 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\HumanResources;

use App\Models\Helpers\Contact;
use App\Models\System\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @mixin IdeHelperEmployee
 */
class Employee extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;


    protected $casts = [
        'data'          => 'array',
        'errors'        => 'array',
        'salary'        => 'array',
        'working_hours' => 'array',

    ];

    protected $attributes = [
        'data'          => '{}',
        'errors'        => '{}',
        'salary'        => '{}',
        'working_hours' => '{}',
    ];

    protected $guarded = [];


    public function getNameAttribute()
    {
        return $this->contact->name;
    }

    public function contact(): MorphOne
    {
        return $this->morphOne(Contact::class, 'contactable');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function supervisors(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class,'supervisors','employee_id','supervisor_id')->using(Supervisor::class)->withTimestamps();
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class,'supervisors','supervisor_id','employee_id')->using(Supervisor::class)->withTimestamps();
    }

}

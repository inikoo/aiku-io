<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 13 Sep 2021 18:02:59 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\HumanResources;

use App\Models\Helpers\Attachment;
use App\Models\Helpers\Contact;
use App\Models\Media\Image;
use App\Models\System\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperEmployee
 */
class Employee extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use Searchable;


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

    public function jobPositions(): BelongsToMany
    {
        return $this->belongsToMany(JobPosition::class)->withTimestamps();
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'image_model', 'imageable_type', 'imageable_id');
    }
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachment_model', 'attachmentable_type', 'attachmentable_id');
    }

    public function workTargets(): HasMany
    {
        return $this->hasMany(WorkTarget::class);
    }

    public function homeOffice(): morphOne
    {
        return $this->morphOne(Workplace::class, 'owner');
    }

    public function clockings(): MorphMany
    {
        return $this->morphMany(Clocking::class, 'clockable');
    }

    public function createdClockings(): MorphMany
    {
        return $this->morphMany(Clocking::class, 'generator');
    }

    public function deletedClockings(): MorphMany
    {
        return $this->morphMany(Clocking::class, 'deleter');
    }

}

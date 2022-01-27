<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 18:49:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Account;

use App\Models\Assets\Language;
use App\Models\Procurement\Agent;
use App\Models\Procurement\Supplier;
use App\Models\Helpers\Contact;
use App\Models\HumanResources\Workplace;
use App\Models\Inventory\Stock;
use App\Models\Production\Workshop;
use App\Models\System\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * @mixin IdeHelperTenant
 */
class Tenant extends SpatieTenant
{
    use HasSlug;

    protected $attributes = [
        'data' => '{}',
    ];
    protected $casts = [
        'data' => 'array'
    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::updated(function ($tenant) {
            if ($tenant->wasChanged('language_id')) {
                $tenant->user->update([
                                          'language_id' => $tenant->language_id
                                      ]);
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('database')
            ->saveSlugsTo('nickname')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function businessType(): BelongsTo
    {
        return $this->belongsTo('App\Models\Account\BusinessType');
    }

    public function contact(): MorphOne
    {
        return $this->morphOne(Contact::class, 'contactable');
    }

    public function accountUser(): MorphOne
    {
        return $this->morphOne(AccountUser::class, 'userable');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function agents(): MorphMany
    {
        return $this->morphMany(Agent::class, 'owner');
    }

    public function suppliers(): MorphMany
    {
        return $this->morphMany(Supplier::class, 'owner');
    }

    public function workshops(): MorphMany
    {
        return $this->morphMany(Workshop::class, 'owner');
    }

    public function workplaces(): MorphMany
    {
        return $this->morphMany(Workplace::class, 'owner');
    }

    public function stocks(): MorphMany
    {
        return $this->morphMany(Stock::class, 'owner');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function getCodeAttribute(): string
    {
        return Str::snake(app('currentTenant')->name, '-');
    }


}

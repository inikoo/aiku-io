<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 29 Mar 2022 01:34:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


namespace App\Models\Auth;



use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperWebsiteUser
 */
class WebsiteUser extends Model
{
    use UsesTenantConnection;
    use HasApiTokens;

    protected $guarded=[];

    protected static function booted()
    {
        static::created(
            function (WebsiteUser $websiteUser) {
                $websiteUser->iris_api_key=$websiteUser->createToken('aiku-user',[])->plainTextToken;
                $websiteUser->save();
            }
        );
        static::deleting(
            function (WebsiteUser $websiteUser) {
                $websiteUser->tokens()->delete();
                $websiteUser->iris_api_key=null;
                $websiteUser->save();

            }
        );

    }



}


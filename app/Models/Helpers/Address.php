<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 02 Oct 2020 00:31:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

namespace App\Models\Helpers;

use App\Models\Assets\Country;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperAddress
 */
class Address extends Model implements Auditable
{
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = [];


    protected static function booted()
    {
        static::created(

            function (Address $address) {

                if ($country = (new Country())->firstWhere('id', $address->country_id)) {
                    $address->country_code = $country->code;
                    $address->checksum=$address->getChecksum();
                    $address->save();
                }
            }
        );
    }


    public function owner(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'owner_type', 'owner_id');
    }


    public function getChecksum(): string
    {
        return md5(
            json_encode(
                array_map(
                    'strtolower',
                    array_diff_key(
                        $this->toArray(),
                        array_flip(
                            [
                                'id',
                                'data',
                                'settings',
                                'contact',
                                'organization',
                                'country_code',
                                'checksum',
                                'created_at',
                                'updated_at',
                                'owner_id',
                                'owner_type'
                            ]
                        )
                    )
                )
            )
        );
    }

    public function deleteIfOrphan()
    {
        if (!DB::table('addressables')->where('address_id', $this->id)->exists()) {
            try {
                $this->delete();
            } catch (Exception) {
                //
            }
        }
    }


}

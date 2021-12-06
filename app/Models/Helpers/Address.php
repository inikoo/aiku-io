<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 02 Oct 2020 00:31:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

namespace App\Models\Helpers;

use App\Models\Assets\Country;
use App\Models\CRM\Customer;
use CommerceGuys\Addressing\Address as Adr;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
use CommerceGuys\Addressing\Country\CountryRepository;
use CommerceGuys\Addressing\Formatter\DefaultFormatter;
use CommerceGuys\Addressing\ImmutableAddressInterface;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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

                    $address->checksum = $address->getChecksum();
                    $address->save();
                }
            }
        );
    }


    private function getAdr(): ImmutableAddressInterface|Adr
    {
        $address = new Adr();


        return $address
            ->withCountryCode($this->country_code)
            ->withAdministrativeArea($this->administrative_area)
            ->withDependentLocality($this->dependant_locality)
            ->withLocality($this->locality)
            ->withPostalCode($this->postal_code)
            ->withSortingCode($this->sorting_code)
            ->withAddressLine2($this->address_line_2)
            ->withAddressLine1($this->address_line_1);
    }

    /** @noinspection PhpUnused */
    public function getFormattedAddressAttribute(): string
    {
        $addressFormatRepository = new AddressFormatRepository();
        $countryRepository       = new CountryRepository();
        $subdivisionRepository   = new SubdivisionRepository();


        $formatter = new DefaultFormatter($addressFormatRepository, $countryRepository, $subdivisionRepository, ['html' => false]);


        return $formatter->format($this->getAdr());
    }

    public function getChecksum(): string
    {


        return md5(
            json_encode(
                array_filter(
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
                                    'owner_type',
                                    'immutable'
                                ]
                            )
                        )
                    )
                )
            )
        );
    }

    public function customers(): MorphToMany
    {
        return $this->morphedByMany(Customer::class, 'addressable');
    }


    public function owner(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'owner_type', 'owner_id');
    }


}

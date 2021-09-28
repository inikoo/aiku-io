<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 23 Aug 2021 18:02:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Assets\Country;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
use CommerceGuys\Addressing\Country\CountryRepository;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addressFormatRepository = new AddressFormatRepository();
        $subdivisionRepository   = new SubdivisionRepository();

        $countriesData = [];

        foreach (['continent', 'names', 'iso3', 'capital', 'phone'] as $field) {
            foreach (json_decode(file_get_contents("http://country.io/$field.json")) as $countryCode => $value) {
                $countriesData[$countryCode][$field] = $value;
            }
        }

        $otherCountriesData['CS']=[
            'names'=>'Czechoslovakia',
            'iso3'=>null,
            'continent'=>null,
            'capital'=>'Prague',
            'phone'=>null
        ];
        $countriesData=array_merge($countriesData,$otherCountriesData);

        foreach ($countriesData as $countryCode => $countryData) {
            Country::UpdateOrCreate(
                ['code' => $countryCode],
                [
                    'name'       => $countryData['names'],
                    'iso3'       => $countryData['iso3'],
                    'continent'  => $countryData['continent'],
                    'capital'    => $countryData['capital'],
                    'phone_code' => $countryData['phone'],
                ]
            );
        }

        $countryRepository = new CountryRepository();

        $countryList = $countryRepository->getList('en-GB');
        foreach ($countryList as $countryCode => $countryName) {
            $_country = $countryRepository->get($countryCode);


            $country = Country::UpdateOrCreate(
                ['code' => $countryCode],
                [
                    'name' => $countryName,
                    'iso3' => $_country->getThreeLetterCode()
                ]
            );


            $addressFormat = $addressFormatRepository->get($countryCode);
            $data          = $country->data;
            //$data['used_fields']     = $addressFormat->getUsedFields();

            $data['required_fields']      = $addressFormat->getRequiredFields();
            $data['administrative_areas'] = [];

            $data['fields'] = [];
            foreach ($addressFormat->getUsedFields() as $usedField) {
                $localityLabel = $addressFormat->getLocalityType() ?? 'city';
                if ($localityLabel == 'post_town') {
                    $localityLabel = 'post town';
                }
                $administrativeAreaLabel = $addressFormat->getAdministrativeAreaType() ?? 'administrative area';
                if ($administrativeAreaLabel == 'do_si') {
                    $administrativeAreaLabel = 'do si';
                }
                $dependentLocalityLabel = $addressFormat->getDependentLocalityType() ?? 'dependent locality';
                if ($dependentLocalityLabel == 'village_township') {
                    $dependentLocalityLabel = 'village/township';
                }

                $postalCodeLabel = $addressFormat->getPostalCodeType() ?? 'postal';
                if ($postalCodeLabel == 'postal') {
                    $postalCodeLabel = 'postal code';
                }

                $fieldLabel = match ($usedField) {
                    'organization', 'givenName', 'familyName' => false,
                    'administrativeArea' => $administrativeAreaLabel,
                    'locality' => $localityLabel,
                    'dependentLocality' => $dependentLocalityLabel,
                    'postalCode' => $postalCodeLabel,
                    'sortingCode' => 'sorting code',
                    'addressLine1' => 'address',
                    'addressLine2' => 'address line 2',

                    default => $usedField
                };
                if ($fieldLabel) {
                    $data['fields'][$usedField] = [
                        'label' => $fieldLabel,
                    ];

                    if (in_array($usedField, $addressFormat->getRequiredFields())) {
                        $data['fields'][$usedField]['required'] = true;
                    }
                }
            }

            $tmp = [];
            foreach ($data['fields'] as $key => $value) {
                $_key       = Str::snake(preg_replace('/addressLine/', 'addressLine_', $key));
                $tmp[$_key] = $value;
            }
            $data['fields'] = $tmp;


            foreach ($subdivisionRepository->getAll([$countryCode]) as $subDivision) {
                $data['administrative_areas'][] = [
                    'name'     => $subDivision->getName(),
                    'code'     => $subDivision->getCode(),
                    'iso_code' => $subDivision->getIsoCode()
                ];
            }

            $data['identity_document_type'] = match ($countryCode) {
                'GB' => [
                    [
                        'value' => 'ninp',
                        'name'  => 'National Insurance number'
                    ]
                ],
                'MY' => [
                    [
                        'value' => 'mykad',
                        'name'  => 'MyKad'
                    ]
                ],
                'MX' => [
                    [
                        'value' => 'ife',
                        'name'  => 'IFE'
                    ],
                    [
                        'value' => 'curp',
                        'name'  => 'CURP'
                    ]
                ],
                default => []
            };


            $country->data = $data;
            $country->save();
        }
    }
}

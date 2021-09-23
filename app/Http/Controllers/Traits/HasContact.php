<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 13 Sep 2021 21:40:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */
namespace App\Http\Controllers\Traits;


use App\Models\Assets\Country;
use Illuminate\Support\Arr;

trait HasContact{
    private function getDefaultCountry(): Country
    {
        $countryID            = app('currentTenant')->cointry_id ?? Country::firstWhere('code', config('app.country'))->id;
        return Country::find($countryID);
    }

    private function getDefaultDocumentTypes($country): array
    {

        $documentTypes=[
            [
                'value' => 'Passport',
                'name'  => __('Passport')
            ],
            [
                'value'   => 'Other',
                'name'    => __('Other'),
                'isOther' => true
            ]
        ];


        if (Arr::get($country->data, 'identity_document_type')) {
            $documentTypes = array_merge(
                Arr::get($country->data, 'identity_document_type'),
                $documentTypes
            );
        }

        return $documentTypes;
    }
}



<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 03:16:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use App\Models\Utils\ActionResult;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;
use stdClass;


class MigrateModel
{

    use AsAction;


    protected object $auModel;
    protected ?object $parent;
    protected array $modelData;
    protected ?Model $model = null;
    protected string $aiku_id_field;
    protected bool $ignore;


    #[Pure] public function __construct()
    {
        $this->auModel       = new stdClass();
        $this->aiku_id_field = 'aiku_id';
        $this->ignore        = false;
    }

    public function getParent()
    {
        return null;
    }

    protected function parseModelData()
    {
    }

    protected function setModel()
    {
    }

    #[Pure] protected function updateModel(): ActionResult
    {
        return new ActionResult();
    }

    #[Pure] protected function storeModel(): ActionResult
    {
        return new ActionResult();
    }

    protected function migrateImages()
    {
        return null;
    }

    protected function migrateAttachments()
    {
        return null;
    }

    protected function postMigrateActions(ActionResult $res): ActionResult
    {
        return $res;
    }

    protected function handle($auModel): ActionResult
    {
        $this->auModel->data = $auModel;
        $this->parent        = $this->getParent();
        $this->parseModelData();


        if ($this->auModel->data->{$this->aiku_id_field}) {
            $this->setModel();

            $res = $this->updateModel();

            if ($res->changes) {
                $resource=new  ActionResultResource($res);
                print_r($resource->resolve());
            }

            if ($res->status == 'deleted') {
                $this->updateAuroraModel();

                return $res;
            } elseif ($res->status == 'error') {
                return $res;
            }
        } else {
            $res = $this->storeModel();
            if ($res->status == 'error') {
                return $res;
            }

            $this->updateAuroraModel($res->model_id);
        }


        $this->model = $res->model;


        $this->migrateImages();
        $this->migrateAttachments();

        return $this->postMigrateActions($res);
    }

    protected function setAuroraConnection($database_name)
    {
        $database_settings = data_get(config('database.connections'), 'aurora');
        data_set($database_settings, 'database', $database_name);
        config(['database.connections.aurora' => $database_settings]);
        DB::connection('aurora');
        DB::purge('aurora');
    }

    protected function sanitizeData($data): array
    {
        return array_filter($data, fn($value) => !is_null($value) && $value !== ''
            && $value != '0000-00-00 00:00:00'
            && $value != '2018-00-00 00:00:00'
        );
    }

    protected function getDate($value): string
    {
        return ($value != '' && $value != '0000-00-00 00:00:00'
            && $value != '2018-00-00 00:00:00') ? Carbon::parse($value)->format('Y-m-d') : '';
    }

    protected function parseLanguageID($locale): int|null
    {
        if ($locale != '') {
            try {
                return Language::where(
                    'code',
                    match ($locale) {
                        'zh_CN.UTF-8' => 'zh-CN',
                        default => substr($locale, 0, 2)
                    }
                )->first()->id;
            } catch (Exception) {
                print "Locale $locale not found\n";

                return null;
            }
        }

        return null;
    }

    protected function parseCurrencyID($currencyCode): int|null
    {
        if ($currencyCode != '') {
            if ($currencyCode == 'LEU') {
                $currencyCode = 'RON';
            }

            return Currency::where('code', $currencyCode)->firstOrFail()->id;
        }

        return null;
    }

    protected function parseTimezoneID($timezone): int|null
    {
        if ($timezone != '') {
            return Timezone::where('name', $timezone)->first()->id;
        }

        return null;
    }

    protected function parseCountryID($country): int|null
    {
        if ($country != '') {
            try {
                if (strlen($country) == 2) {
                    return Country::withTrashed()->where('code', $country)->firstOrFail()->id;
                } elseif (strlen($country) == 3) {
                    return Country::withTrashed()->where('iso3', $country)->firstOrFail()->id;
                } else {
                    return Country::withTrashed()->where('name', $country)->firstOrFail()->id;
                }
            } catch (Exception) {
                print "Country $country not found\n";

                return null;
            }
        }

        return null;
    }

    protected function parseAddress($prefix, $auAddressData): array
    {
        $addressData                        = [];
        $addressData['address_line_1']      = (string)Str::of($auAddressData->{$prefix.' Address Line 1'} ?? null)->limit(251);
        $addressData['address_line_2']      = (string)Str::of($auAddressData->{$prefix.' Address Line 2'} ?? null)->limit(251);
        $addressData['sorting_code']        = (string)Str::of($auAddressData->{$prefix.' Address Sorting Code'} ?? null)->limit(187);
        $addressData['postal_code']         = (string)Str::of($auAddressData->{$prefix.' Address Postal Code'} ?? null)->limit(187);
        $addressData['locality']            = (string)Str::of($auAddressData->{$prefix.' Address Locality'} ?? null)->limit(187);
        $addressData['dependant_locality']  = (string)Str::of($auAddressData->{$prefix.' Address Dependent Locality'} ?? null)->limit(187);
        $addressData['administrative_area'] = (string)Str::of($auAddressData->{$prefix.' Address Administrative Area'} ?? null)->limit(187);
        $addressData['country_id']          = $this->parseCountryID($auAddressData->{$prefix.' Address Country 2 Alpha Code'} ?? null);

        return $addressData;
    }


    protected function getModelImagesCollection($model, $id): Collection
    {
        return DB::connection('aurora')
            ->table('Image Subject Bridge')
            ->leftJoin('Image Dimension', 'Image Subject Image Key', '=', 'Image Key')
            ->where('Image Subject Object', $model)
            ->where('Image Subject Object Key', $id)
            ->orderByRaw("FIELD(`Image Subject Is Principal`, 'Yes','No')")
            ->get();
    }

    protected function getModelAttachmentsCollection($model, $id): Collection
    {

        return DB::connection('aurora')
            ->table('Attachment Bridge as B')
            ->leftJoin('Attachment Dimension as A', 'A.Attachment Key', '=', 'B.Attachment Key')
            ->where('Subject', $model)
            ->where('Subject Key', $id)
            ->get();
    }

    private function updateAuroraModel($value = null)
    {
        if ($this->ignore) {
            return;
        }
        DB::connection('aurora')->table($this->auModel->table)
            ->where($this->auModel->id_field, $this->auModel->id)
            ->update([$this->aiku_id_field => $value]);
    }
}

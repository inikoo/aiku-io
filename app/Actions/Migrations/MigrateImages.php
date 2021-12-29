<?php

namespace App\Actions\Migrations;

use App\Models\Buying\Agent;
use App\Models\Buying\Supplier;
use App\Models\HumanResources\Employee;
use App\Models\Inventory\Stock;
use App\Models\Media\CommunalImage;
use App\Models\Media\Image;
use App\Models\System\User;
use App\Models\Trade\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateImages
{
    use AsAction;

    public function handle(Product|User|Employee|Supplier|Agent|Stock $model, $imagesData)
    {
        $old = [];
        $new = [];

        $model->images()->get()->each(
            function ($image) use (&$old) {
                $old[] = $image->id;
            }
        );
        $rank = 1;

        foreach ($imagesData as $imageData) {
            if ($imageData->{'communal_image_id'}) {
                $scope = strtolower($imageData->{'Image Subject Object Image Scope'});


                Image::upsert([
                                  [
                                      'imageable_type'    => $model->getMorphClass(),
                                      'imageable_id'      => $model->id,
                                      'scope'             => $scope,
                                      'caption'           => $imageData->{'Image Subject Image Caption'},
                                      'created_at'        => $imageData->{'Image Subject Date'},
                                      'filename'          => Str::of($imageData->{'Image Filename'})->limit(255),
                                      'communal_image_id' => $imageData->{'communal_image_id'},
                                      'aurora_id'         => $imageData->{'Image Subject Key'},
                                      'rank'              => $rank,
                                      'compression'       => '{}'
                                  ],
                              ],
                              ['communal_image_id', 'imageable_id', 'imageable_type', 'scope'],
                              ['filename']
                );


                $image = Image::where('imageable_type', $model->getMorphClass())
                    ->where('imageable_id', $model->id)
                    ->where('communal_image_id', $imageData->{'communal_image_id'})
                    ->where('scope', $scope)
                    ->first();

                if ($image) {


                    DB::connection('aurora')->table('Image Subject Bridge')
                        ->where('Image Subject Key', $imageData->{'Image Subject Key'})
                        ->update(['aiku_id' => $image->id]);

                    $new[] = $image->id;
                    $model->images()->save($image);
                    $rank--;

                    $communalImage = CommunalImage::find($imageData->{'communal_image_id'});

                    try {
                        $communalImage->tenants()->attach(App('currentTenant')->id);
                    } catch (Exception) {
                        //dd($e->getMessage());
                    }
                } else {
                    dd([
                           'imageable_type'    => $model->getMorphClass(),
                           'imageable_id'      => $model->id,
                           'scope'             => $scope,
                           'caption'           => $imageData->{'Image Subject Image Caption'},
                           'filename'          => Str::of($imageData->{'Image Filename'})->limit(255),
                           'communal_image_id' => $imageData->{'communal_image_id'},
                           'aurora_id'         => $imageData->{'Image Subject Key'},
                           'rank'              => $rank
                       ]);
                }
            }
        }

        $model->images()->whereIn('id', array_diff($old, $new))->delete();
    }
}

<?php

namespace App\Actions\Migrations;

use App\Models\Helpers\ImageModel;
use App\Models\Helpers\Product;
use App\Models\System\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateImageModels
{
    use AsAction;

    public function handle(Model|Product|User $model, $imageModelsData)
    {
        $old = [];
        $new = [];

        $model->images()->get()->each(
            function ($imageModel) use (&$old) {
                $old[] = $imageModel->id;
            }
        );
        $rank = 1;

        foreach ($imageModelsData as $imageModelData) {
            if ($imageModelData->{'image_id'}) {
                $scope = strtolower($imageModelData->{'Image Subject Object Image Scope'});

                try {
                    $imageModel = (new ImageModel())->updateOrCreate(
                        [
                            'imageable_type' => $model->getMorphClass(),
                            'imageable_id'   => $model->id,
                            'scope'          => $scope,
                            'filename'       => Str::of($imageModelData->{'Image Filename'})->limit(255),
                            'image_id'       => $imageModelData->{'image_id'},
                            'aurora_id'      => $imageModelData->{'Image Subject Key'}

                        ], [

                            'rank' => $rank
                        ]
                    );
                    $new[]      = $imageModel->id;
                    $model->images()->save($imageModel);
                    $rank--;
                } catch (Exception) {
                    //print "Skipping duplicated image\n";
                }
            }
        }


        $model->images()->whereIn('id', array_diff($old, $new))->delete();
    }
}

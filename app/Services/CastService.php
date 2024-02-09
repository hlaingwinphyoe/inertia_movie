<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class CastService
{
    public function storeOrUpdate(object $model, array $paramData = [])
    {
        $model->name = $paramData['name'];
        $model->birthday = $paramData['birthday'];
        $model->gender = $paramData['gender'];
        $model->biography = $paramData['biography'];

        return $model;
    }

    public function destroyImage(object $model)
    {
        //image
        if (public_path($model->profile)) {
            File::delete(public_path($model->profile));
        }
    }
}

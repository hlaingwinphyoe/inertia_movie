<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaService
{
    public function storeMedia(array $formData = [])
    {
        try {
            DB::beginTransaction();

            $file = $formData['media'];

            $fileNameWithExt = $file->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $fileNameToStore = $fileName . '_' . time() . '.' . $file->extension();

            //store file
            if (!Storage::exists('public/' . $formData['folder'])) {
                Storage::makeDirectory('public/' . $formData['folder']);
            }

            $url = 'storage/' . $formData['folder'] . '/' . $fileNameToStore;


            if ($formData['type'] === 'image') {
                $img = Image::make($file);
                $file = $img->fit(270, 350);
                $file->save($url);

                $url = Storage::url('public/' . $formData['folder'] . '/' . $fileNameToStore);
            } else {
                $url = $file->storeAs('public', $fileNameToStore);

                $fileName = $fileName . '.' . $file->extension();

                Storage::move($url, 'public/' . $formData['folder'] . '/' . $fileNameToStore); //tmp

                $url = Storage::url('public/' . $formData['folder'] . '/' . $fileNameToStore);
            }


            DB::commit();

            return $url;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}

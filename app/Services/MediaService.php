<?php

namespace App\Services;

use App\Models\Media;
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
            if (!Storage::exists('public/' . $formData['type'])) {
                Storage::makeDirectory('public/' . $formData['type']);
            }

            $url = 'storage/' . $formData['type'] . '/' . $fileNameToStore;


            $img = Image::make($file);
            $file = $img->fit(200, 250);
            $file->save($url);

            $url = Storage::url('public/' . $formData['type'] . '/' . $fileNameToStore);

            DB::commit();

            return $url;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function storeMultiMedia(array $formData = [])
    {
        try {
            DB::beginTransaction();
            $medias = array();

            foreach ($formData['medias'] as $media) {

                $file = $media;
                $fileNameWithExt = $file->getClientOriginalName();

                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

                $fileNameToStore = $fileName . '_' . time() . '.' . $file->extension();

                $url = $file->storeAs('public', $fileNameToStore);

                $fileName = $fileName . '.' . $file->extension();

                Storage::move($url, 'public/' . $formData['type'] . '/' . $fileNameToStore); //tmp

                $thumbnailPath = public_path('storage/' . $formData['type'] . '/' . $fileNameToStore);

                $img = Image::make($thumbnailPath)->fit(200, 250);

                $img->save($thumbnailPath);

                $url = Storage::url('public/' . $formData['type'] . '/' . $fileNameToStore);

                // 2: store media data
                $media = Media::create([
                    'name' => $fileName,
                    'slug' => $fileNameToStore,
                    'url' => $url,
                    'ext' => $file->extension(),
                    'type' => $formData['type'],
                ])->toArray(); // put toArray() for illegal type offset error

                $medias[] = $media['id'];
            }

            DB::commit();

            return $medias;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function destroyMedia(int $mediaId)
    {
        try {
            $media = Media::findOrFail($mediaId);

            if (Storage::exists($media->url)) {
                Storage::delete($media->url);
            }

            if (Storage::exists('public/' . $media->type . '/' . $media->slug)) {
                Storage::delete('public/' . $media->type . '/' . $media->slug);
            }

            $media->delete();

            return "";
        } catch (\Exception $e) {

            throw $e;
        }
    }
}

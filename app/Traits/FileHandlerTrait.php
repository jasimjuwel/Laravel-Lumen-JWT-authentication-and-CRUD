<?php

namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;
use Intervention\Image\Facades\Image;

trait FileHandlerTrait
{
    /**
     * Process User Image
     *
     * @param Request $request
     * @param $key
     * @param $path
     * @param $width
     * @param $height
     * @param $thumbDetails
     *
     * @param $crop_resize = false -> nothing will happen
     * @param $crop_resize = crop -> will crop only
     * @param $crop_resize = resize -> will resize only
     * @param $crop_resize = resize -> will resize only
     * @param $crop_resize = both -> will do both
     *
     * @return null|string
     */
    protected function processImage(Request $request, $key, $path, $width, $height, $crop_resize = false)
    {
        try {

            $fileName = null;

            if ($request->hasFile($key)) {

                $fileName = uniqid() . Str::random(5) . time() . '.' . $request->file($key)->getClientOriginalExtension();

                if (!$this->isReallyImage($request->file($key)->getClientOriginalExtension())) {
                    // throw new \Exception("NOT AN IMAGE!");
                    return null;
                }

                $image_thumb = Image::make($request->file($key));

                if ($crop_resize) {
                    if ($crop_resize === 'crop') {
                        $image_thumb->crop($width, $height);
                    } elseif ($crop_resize === 'resize') {
                        $image_thumb->resize($width, $height);
                    } elseif ($crop_resize === 'both') {
                        $image_thumb->crop($width, $height)
                            ->resize($width, $height);
                    } else {
                        throw new \Exception('Wrong parameter for resize and crop action.');
                    }
                }

                $destinationPath = config('siteConfig.upload_dir') . $path; // upload path
                File::makeDirectory($destinationPath, 0777, true, true);
                $image_thumb->save($destinationPath . $fileName);

                return $path . $fileName;
            }

            return null;

        } catch (\Exception $e) {
            Log::error($e);

            return null;
        }
    }

    protected function isReallyImage($extension)
    {
        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {

            return true;
        }

        return false;
    }
}

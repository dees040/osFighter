<?php

namespace App\Models\Traits;

use File;
use Illuminate\Http\UploadedFile;
use Image;
use Illuminate\Database\Eloquent\Model;

trait Imageable
{
    public static function bootImageable()
    {
        // Destroy the crime image before deleting.
        static::deleting(function(Model $model) {
            $model->deleteImage();
        });
    }

    /**
     * Get the image folder name for a model.
     *
     * @return string
     */
    private function getFolderName()
    {
        return strtolower(str_plural((new \ReflectionClass($this))->getShortName()));
    }

    /**
     * Get path to image.
     *
     * @return string
     */
    public function getPath()
    {
        return 'images/game/'.$this->getFolderName().'/' . $this->id . '.jpg';
    }

    /**
     * Store a new image for the current model.
     *
     * @param UploadedFile $image
     */
    public function storeImage(UploadedFile $image)
    {
        Image::make($image)->encode('jpg')->save(public_path($this->getPath()));
    }

    /**
     * Delete the image associated with the model.
     */
    public function deleteImage()
    {
        File::delete(public_path($this->getPath()));
    }

    /**
     * Get the path/url to the model image.
     *
     * @return string
     */
    public function image()
    {
        return asset($this->getPath());
    }
}
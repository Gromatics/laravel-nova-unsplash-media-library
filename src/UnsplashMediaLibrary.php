<?php

namespace Gromatics\LaravelNovaUnsplashMediaLibrary;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;

class UnsplashMediaLibrary extends Field
{
    protected $defaultValidatorRules = ['image'];

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'laravel-nova-unsplash-media-library';

    /**
     * @param $name
     * @param $mediaCollection
     * @param $disk
     * @param $storageCallback
     */
    public function __construct($name, $mediaCollection = null, $disk = null, $storageCallback = null)
    {
        parent::__construct($name, $mediaCollection);
        $this->withMeta(['unsplashClientId' => config('unsplash-media-library.unsplash_client_id')]);
        $this->saveImage($name, $disk);
    }

    /**
     * @param string $name
     * @param $disk
     * @return void
     */
    private function saveImage(string $name, $disk = null)
    {
        $mediaCollection = $mediaCollection ?? str_replace(' ', '_', Str::lower($name));
        $this->fillUsing(function ($request, $model, $attribute, $requestAttribute) use ($disk, $mediaCollection) {
            if (isset($request->{$attribute})) {
                if ($request->{$attribute}) {
                    $model->clearMediaCollection($attribute);
                    if ($disk) {
                        $model->addMediaFromUrl($request->{$attribute})
                            ->disk($disk)
                            ->toMediaCollection($mediaCollection);
                    } else {
                        $model->addMediaFromUrl($request->{$attribute})
                            ->toMediaCollection($mediaCollection);
                    }
                }
            } else {
                $model->clearMediaCollection($attribute);
            }
        });
    }

    protected function resolveAttribute($resource, $attribute)
    {
        return $resource->getFirstMediaUrl($attribute, 'thumb');
    }

}

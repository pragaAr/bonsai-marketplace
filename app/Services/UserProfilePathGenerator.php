<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class UserProfilePathGenerator implements PathGenerator
{
  public function getPath(Media $media): string
  {
    return $this->getBasePath($media).'/';
  }

  public function getPathForConversions(Media $media): string
  {
    return $this->getBasePath($media).'/conversions/';
  }

  public function getPathForResponsiveImages(Media $media): string
  {
    return $this->getBasePath($media).'/responsive-images/';
  }

  protected function getBasePath(Media $media): string
  {
    return "users/{$media->model_id}/profile/{$media->id}";
  }
}

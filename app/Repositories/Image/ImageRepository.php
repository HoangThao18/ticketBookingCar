<?php

namespace App\Repositories\Image;

use App\Repositories\BaseRepository;
use App\Repositories\CarImgs\CarImgsRepositoryInterface;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Image::class;
  }
}

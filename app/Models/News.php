<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $table = "news";

    protected $fillable = ['title', 'summary', 'content', "active"];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getMorphClass()
    {
        return 'news';
    }

    public function saveImage($path)
    {
        return $this->images()->create(['url' => $path]);
    }
}

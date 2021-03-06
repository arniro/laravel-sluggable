<?php


namespace Arniro\Sluggable\Tests\Fixtures;


use Arniro\Sluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations, Sluggable;

    protected $table = 'products';

    protected $guarded = [];

    protected function sluggable()
    {
        return 'name';
    }
}

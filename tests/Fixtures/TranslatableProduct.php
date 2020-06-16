<?php


namespace Arniro\Sluggable\Tests\Fixtures;


use Arniro\Sluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TranslatableProduct extends Model
{
    use HasTranslations, Sluggable;

    protected $table = 'products';

    protected $guarded = [];

    public $translatable = ['name', 'slug'];

    protected function sluggable()
    {
        return 'name';
    }
}

<?php


namespace Arniro\Sluggable\Tests\Fixtures;


use Arniro\Sluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Sluggable;

    protected $table = 'products';

    protected $guarded = [];

    protected function sluggable()
    {
        return 'name';
    }
}

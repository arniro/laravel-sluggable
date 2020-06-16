# Generate a slug for your Eloquent models

## Installation 

Install the package via composer:

`composer require arniro/laravel-sluggable`

## Usage

Include a `Sluggable` trait to the model:

```php
use Arniro\Sluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Sluggable;

    //
}
```

Whenever you create or update a model, a slug will be generated from the `name` column to the `slug` column by default.

```php
Product::create([
    'name' => 'Lorem Ipsum'
]);
```

A `slug` column will be filled with the following value: `lorem-ipsum`. The slug value will always be unique.

You can override a column name that is used to generate a slug from with the `getSluggable` method:

```php
use Arniro\Sluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Sluggable;

    public function getSluggable()
    {
        return 'title';
    }

    //
}
```

## Translatable columns

If your column is translatable and you use 
a [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) package 
it will be recognized and used behind the scenes so all you need to do is to add your 
slug column name to the `translatable` property of your model:

```php
use Arniro\Sluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations, Sluggable;

    public $translatable = ['name', 'slug'];

    //
}
```

So now if you're creating or updating a sluggable column, all of its locales will be slugged:

```php
$product = Product::create([
    'name' => [
        'en' => 'Lorem Ipsum',
        'ru' => 'Lorem Ipsum Ru'
    ]
]);
$product->getTranslations('slug'); // ['en' => 'lorem-ipsum','ru' => 'lorem-ipsum-ru']
```

## Testing 

```
composer test
```

## License 

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

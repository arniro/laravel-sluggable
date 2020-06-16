<?php

namespace Arniro\Sluggable\Tests;

use Arniro\Sluggable\Tests\Fixtures\Product;
use Arniro\Sluggable\Tests\Fixtures\TranslatableProduct;
use Spatie\Translatable\HasTranslations;

class SluggableTest extends TestCase
{
    /** @test */
    public function it_saves_slug_when_model_is_creating()
    {
        $product = Product::create(['name' => 'Some product']);

        $this->assertEquals('some-product', $product->slug);
    }

    /** @test */
    public function it_saves_slug_when_model_is_updating()
    {
        $product = Product::create(['name' => 'Some product']);

        $product->update(['name' => 'changed']);

        $this->assertEquals('changed', $product->slug);
    }

    /** @test */
    public function a_base_column_can_be_overriden()
    {
        $product = new class extends Product {
            protected function getSluggable()
            {
                return 'alias';
            }
        };

        $product->name = 'Some product';
        $product->alias = 'Some product alias';
        $product->save();

        $this->assertEquals('some-product-alias', $product->slug);
    }

    /** @test */
    public function a_generated_slug_should_be_unique()
    {
        Product::create(['name' => 'product']);
        $secondProduct = Product::create(['name' => 'product']);
        $thirdProduct = Product::create(['name' => 'product']);

        $this->assertEquals('product-1', $secondProduct->slug);
        $this->assertEquals('product-2', $thirdProduct->slug);
    }

    /** @test */
    public function slug_should_not_be_changed_if_the_value_is_not_changed_during_update()
    {
        $product = Product::create(['name' => 'Product']);

        $this->assertEquals('product', $product->slug);

        $product->update(['name' => 'Product']);

        $this->assertEquals('product', $product->slug);
    }

    /** @test */
    public function it_saves_slug_for_all_locales_if_a_column_is_translatable()
    {
        $product = new class extends Product {
            public $translatable = ['name', 'slug'];
        };

        $product->name = [
            'en' => 'Product',
            'ru' => 'Product Ru'
        ];

        $product->save();

        $this->assertEquals('product', $product->translate('slug', 'en'));
        $this->assertEquals('product-ru', $product->translate('slug', 'ru'));
    }

    /** @test */
    public function a_generated_slug_for_a_translatable_attribute_should_be_unique()
    {
        $product = new class extends Product {
            public $translatable = ['name', 'slug'];
        };

        $product->create(['name' => [
            'en' => 'Product',
            'ru' => 'Product Ru'
        ]]);

        $secondProduct = $product->create(['name' => [
            'en' => 'Product',
            'ru' => 'Product Ru'
        ]]);

        $thirdProduct = $product->create(['name' => [
            'en' => 'Product',
            'ru' => 'Product Ru'
        ]]);

        $this->assertEquals('product-1', $secondProduct->translate('slug', 'en'));
        $this->assertEquals('product-ru-1', $secondProduct->translate('slug', 'ru'));

        $this->assertEquals('product-2', $thirdProduct->translate('slug', 'en'));
        $this->assertEquals('product-ru-2', $thirdProduct->translate('slug', 'ru'));
    }

    /** @test */
    public function slug_should_not_be_changed_if_the_value_of_translatable_attribute_is_not_changed_during_update()
    {
        $product = TranslatableProduct::create([
            'name' => [
                'en' => 'Product',
                'ru' => 'Product Ru'
            ]
        ]);

        $product->update([
            'name' => [
                'en' => 'Product',
                'ru' => 'Product Ru'
            ]
        ]);

        $this->assertEquals('product', $product->translate('slug', 'en'));
        $this->assertEquals('product-ru', $product->translate('slug', 'ru'));

        $product->update([
            'name' => [
                'en' => 'changed',
                'ru' => 'Product Ru'
            ]
        ]);

        $this->assertEquals('changed', $product->translate('slug', 'en'));
        $this->assertEquals('product-ru', $product->translate('slug', 'ru'));
    }
}

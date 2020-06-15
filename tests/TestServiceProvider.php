<?php


namespace Arniro\Sluggable\Tests;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--realpath' => realpath(__DIR__.'/Migrations')
        ]);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Santosh
 * Date: 5/5/2019
 * Time: 11:50 AM
 */

namespace Santosh\Sambat;


use Illuminate\Support\ServiceProvider;

class BSDateServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->bind(Converter::class, function (){
            return new Converter();
        });

        $this->app->alias(Converter::class, 'sambat');
    }
}
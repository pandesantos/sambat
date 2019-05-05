<?php
/**
 * Created by PhpStorm.
 * User: Santosh
 * Date: 5/5/2019
 * Time: 11:51 AM
 */

namespace Santosh\Sambat\Facades;


use Illuminate\Support\Facades\Facade;

class Sambat extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sambat';
    }
}
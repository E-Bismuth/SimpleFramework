<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 07/07/2016
 * Time: 10:46
 */

namespace Core\Magic\Debug;


class Debug
{
    public static function die_show($debuged){
        echo '<pre>';print_r($debuged); echo '</pre>';
        die();
    }
    public static function show($debuged){
        echo '<pre>';print_r($debuged); echo '</pre>';
    }

}
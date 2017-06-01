<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 22/11/2016
 * Time: 08:44
 */

namespace Core\Helpers;


class Header
{
    public static function location($url){
        \Core\Magic\Variables\Session\Flash::transfer();
        header("Location: $url");
        exit();
    }
    public static function contentJson(){
        header('Content-Type: application/json');
    }

}
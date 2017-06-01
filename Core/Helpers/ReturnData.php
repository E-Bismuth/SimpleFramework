<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 22/11/2016
 * Time: 08:45
 */

namespace Core\Helpers;


use Core\Magic\Variables\projectDefine\projectDefine;

class ReturnData
{
    public static function BuildSimple($operation,$value){
        $operation = ($operation)? 'success':'failed';
        return ['operation' => $operation, 'value' => $value];
    }
    public static function Success($value){
        if(isset($value['operation'])){
            $operation = ($value['operation'] == 'success')? true:false;
            return $operation;
        }
        return false;
    }
    public static function ReadSimple($value){
        if(isset($value['value'])){
            return $value['value'];
        }
        return [];
    }
    public static function slugify($value){
        $search = [' ','&'];
        $replace = ['-','-'];
        return str_replace($search,$replace,$value);
    }
    public static function generateToken($nbr = 60){
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $nbr)), 0, $nbr);
    }
    public static function generateId($nbr = 11){
        $alphabet = "0123456789AZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $nbr)), 0, $nbr);
    }
    
    public static function namedUrl($routeName,$params = []){
        $Routes = projectDefine::get('ROUTER');

        return projectDefine::get('SITE').$Routes->url($routeName,$params);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 13:29
 */
namespace Core\Magic\Variables;

interface magicVariablesInterface
{
    public static function isExist($field);
    public static function get($field);
    public static function set($field,$value,$force);
    public static function getAll();
}
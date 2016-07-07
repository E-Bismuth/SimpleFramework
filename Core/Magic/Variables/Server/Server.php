<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 13:16
 */

namespace Core\Magic\Variables\Server;


use Core\Magic\Variables\magicVariablesInterface;
use Core\Magic\Variables\variablesException;

/**Take care of $_SERVER values
 * Class Server
 * @package Core\Magic\Variables\Server
 */
class Server implements magicVariablesInterface
{
    /**Get the value of a $_SERVER
     * @param $field
     * @return bool
     */
    public static function get($field){
        if(isset($_SERVER[$field])){
            return $_SERVER[$field];
        }
        else{
            return false;

        }
    }

    /**Set a $_SERVER
     * @param $field
     * @param $value
     * @param bool $force
     * @throws variablesException
     */
    public static function set($field, $value, $force = false){
        if($force == false){
            throw new variablesException('You can\'t set/edit this field ('. $field .') on class Server');
        }
        else{
            $_SERVER[$field] = $value;
        }
    }

    /**Check if the $_SERVER exist
     * @param $field
     * @return bool
     */
    public static function isExist($field)
    {
        if(self::get($field) !== false){
            return true;
        }
        else{
            return false;
        }
    }

    /**Get all the $_SERVER (good for debug)
     * @return mixed
     */
    public static function getAll()
    {
        return $_SERVER;
    }
}

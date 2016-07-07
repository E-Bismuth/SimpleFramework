<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 07/07/2016
 * Time: 09:25
 */

namespace Core\Magic\Variables\projectDefine;


use Core\Magic\Variables\magicVariablesInterface;
use Core\Magic\Variables\variablesException;

/**Store variables/array/object that can't be update
 * Class projectDefine
 * @package Core\Magic\Variables\projectDefine
 */
class projectDefine implements magicVariablesInterface
{
    /**Contain all the variables that can be used
     * @var array
     */
    private static $defined = [];

    /**Get the value of a specific filed
     * @param $field
     * @return array|bool|mixed
     */
    public static function get($field)
    {
        $ArraySession = explode('/',$field);
        if(count($ArraySession)==1){
            if(isset(self::$defined[$field])){
                return self::$defined[$field];
            }
        }
        else{
            $arr = self::$defined;
            foreach ($ArraySession as $session){
                if(array_key_exists($session,$arr)){
                    $arr = $arr[$session];
                }
                else{
                    return false;
                }
            }
            return $arr;
        }
        return false;
    }

    /**Set a project Define
     * ATTENTION !! = as a Define or Const you can't edit
     * @param $field
     * @param $value
     * @param bool $force
     * @throws variablesException
     */
    public static function set($field, $value, $force = false)
    {
        
        if(self::isExist($field)){
            throw new variablesException('You can\'t edit this field ('. $field .') on class projectDefine');
        }
        else{
            $ArraySession = explode('/',$field);
            if(count($ArraySession)==1){
                self::$defined[$field] = $value;
            }
            else{
                $requestSession = '';
                $buildNewSession = array();
                foreach ($ArraySession as $key=>$val){
                    $requestSession .= $val.'/';
                    if($val!=end($ArraySession)){
                        $buildNewSession[$val] = self::get(substr($requestSession,0,-1));
                    }
                    else{
                        $buildNewSession[$val]=$value;
                    }
                }
                for($i=(count($ArraySession) - 2);$i>=0;$i--){
                    $buildNewSession[$ArraySession[$i]][$ArraySession[($i + 1)]] = $buildNewSession[$ArraySession[($i + 1)]];
                }
                self::$defined[$ArraySession[0]] = $buildNewSession[$ArraySession[0]];
            }
        }
    }

    /**Check if the value exist
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

    /**Get all the project variables (good for debug)
     * @return array
     */
    public static function getAll()
    {
        return self::$defined;
    }
}
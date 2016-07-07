<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 16:20
 */

namespace Core\Magic\Variables\Session;


use Core\Magic\Variables\magicVariablesInterface;

/**Take care of $_SESSION values
 * Class Session
 * @package Core\Magic\Variables\Session
 */
class Session implements magicVariablesInterface
{
    /**Get the value of a $_SESSION
     * @param $session
     * @return bool
     */
    public static function get($session)
    {
        $ArraySession = explode('/',$session);
        if(count($ArraySession)==1){
            if(isset($_SESSION[$session])){
                return $_SESSION[$session];
            }
        }
        else{
            $arr = $_SESSION;
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

    /**Set a $_SESSION
     * @param $session
     * @param $value
     * @param bool $force
     */
    public static function set($session, $value, $force = true)
    {
        $ArraySession = explode('/',$session);
        if(count($ArraySession)==1){
            $_SESSION[$session] = $value;
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
            $_SESSION[$ArraySession[0]] = $buildNewSession[$ArraySession[0]];
        }
    }

    /**Check if the $_SESSION exist
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

    /**Get all the $_SESSION (good for debug)
     * @return mixed
     */
    public static function getAll()
    {
        return $_SESSION;
    }
}
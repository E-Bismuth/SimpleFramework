<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 07/07/2016
 * Time: 10:04
 */

namespace Core\Magic\Variables\Session;


/**Session that can be pass only to the next page
 * and will disappear after
 * Class Flash
 * @package Core\Magic\Variables\Session
 */
class Flash extends Session
{
    /**Contain all the session flash that will be passe to the the next page
     * @var array
     */
    private static $defined = [];

    /**Get the value of a special field
     * @param $field
     * @return bool
     */
    public static function get($field)
    {
        return parent::get('Flash/'.$field);
    }

    /** Set a field that will exist on the next page
     * ATTENTION!! this value can't be use directly
     * @param $field
     * @param $value
     * @param bool $force
     */
    public static function set($field, $value, $force = true)
    {
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

    /**Check if the value exist
     * @param $field
     * @return bool
     */
    public static function isExist($field)
    {
        if(self::get('Flash/'.$field) !== false){
            return true;
        }
        else{
            return false;
        }
    }

    /**Get all the array (good for debug)
     * @return bool
     */
    public static function getAll()
    {
        return self::get('Flash');
    }

    /**Will transfer the stored array to the session for the next page
     *
     */
    public static function transfer()
    {
        self::delete();
        if(parent::get('Flash') == false){parent::set('Flash',null,true);}
        parent::set('Flash',self::$defined,true);
    }


    /**Restart the flash session
     *
     */
    public static function delete()
    {
        parent::set('Flash',[]);
    }


}
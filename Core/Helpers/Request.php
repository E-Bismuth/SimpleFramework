<?php

namespace Core\Helpers;
use Core\Magic\Debug\Debug;
use Exception;
/*
 * request parent class for all requests classes (in this DIR)
 * 
 */
class Request
{
	private static $getVars = [];
	private static $postVars = [];
	private static $fileVars = [];

	public static function init(){
		self::$getVars = self::_cleanInputs($_GET);
		self::$postVars = self::_cleanInputs($_POST);

		$_GET = [];
		$_POST = [];
		$_REQUEST=[];
	}

	public static function get($name = null){
		if($name === null){
			return self::$getVars;
		}
		return self::request($name,self::$getVars);
	}
	
	public static function setPost($name,$value){
		self::$postVars[$name] = $value;
	}

	public static function clearPost($name){
        unset(self::$postVars[$name]);
	}

	public static function post($name = null){
		if($name === null){
			return self::$postVars;
		}
		return self::request($name,self::$postVars);
	}

	public static function seeAll(){
		return ['GET'=>self::$getVars,'POST'=>self::$postVars];
	}

	private static function request($name,$method){
		if(array_key_exists($name,$method)){
			return $method[$name];
		}
		return NULL;
	}
	
	 private static function _cleanInputs($data) {
        $clean_input = array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = self::_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 11:24
 */

namespace Core;


use Core\General\autoloadException;
use Core\Magic\Variables\Session\Flash;

class autoload
{
    const BASE_URI = '../';
    const AL_END_EXT = ".php";

    private $ClassName,$ClassFile;

    public function __construct() {
        spl_autoload_register(array($this,'AutoLoadProject'));
    }


    private function AutoLoadReplace() {
        $Code = array("\\");
        $Real = array("/");
        $this->ClassFile = str_replace($Code, $Real, $this->ClassName);

    }
    private function AutoLoadFile() {
        return self::BASE_URI . '/' . $this->ClassFile . self::AL_END_EXT;
    }
    private function AutoLoadProject($name) {
        $this->ClassName = $name;
        $this->AutoLoadReplace();
        if(file_exists($this->AutoLoadFile())){require_once($this->AutoLoadFile()); }
        else{
            throw new autoloadException('Class : '. $name .' not found');
        }
    }

    public static function loadJS($scripts = []) {
        if(count($scripts)!=0){
            $Html = '';
            foreach($scripts AS $js){
                $Html .= "<!-- {$js['Coment']} -->";
                if(is_array($js['Link'])){
                    foreach ($js['Link'] as $link){
                        $Html .= '<script src="' . $link . '"></script>';
                    }
                }
                else{
                    $Html .= '<script src="' . $js['Link'] . '"></script>';
                }
            }
            return $Html;
        }
    }

    public static function loadCSS($scripts = []) {
        if(count($scripts)!=0){
            $Html = '';
            foreach($scripts AS $css){
                $Html .= "<!-- {$css['Coment']} -->";
                if(is_array($css['Link'])){
                    foreach ($css['Link'] as $link){
                        $Html .= '<link href="' . $link . '" rel="stylesheet">';
                    }
                }
                else{
                    $Html .= '<link href="' . $css['Link'] . '" rel="stylesheet">';
                }
            }
            return $Html;
        }
    }

}


new autoload();
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


}


new autoload();
<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 15:05
 */

namespace App\Controller;


use Core\Controller\Controller;
use Core\Magic\Variables\projectDefine\projectDefine;

class appController extends Controller
{

    public function __construct(){
        $this->viewPath = projectDefine::get('ROOT').'/App/Views/';
        $this->templatePath = projectDefine::get('ROOT').'/App/Templates/';
        $this->templateName = projectDefine::get('THEME').'/';
    }

}
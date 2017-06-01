<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 01/06/2017
 * Time: 14:59
 */

namespace App\Controller;



class indexController extends baseController
{

    public function index(){
        $this->render('Home#index');
    }

    public function param($name){
        $this->render('Home#param',['Name'=>$name]);
    }

}
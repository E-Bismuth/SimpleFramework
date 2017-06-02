<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 02/06/2017
 * Time: 12:28
 */
require_once '../Core/autoload.php';
use Core\Magic\Variables\projectDefine\projectDefine;
use Core\Helpers\Request;

Request::init();


projectDefine::set('thisRoot','/'.Request::get('name'));
require_once ('controller.php');

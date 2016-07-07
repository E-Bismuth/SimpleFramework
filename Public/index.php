<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 11:22
 */

session_start();
use Core\Magic\Debug\Debug;
use Core\Magic\Variables\projectDefine\projectDefine;

require_once('../Core/autoload.php');



//$db = new \App\Database\Localhost();
//$db2 = new \App\Database\testDb();
//
//var_dump($db2);
//Debug::die_show($db);



projectDefine::set('ROOT',dirname(__DIR__));
projectDefine::set('THEME','Default');

$routes = new \Core\Router\Router($_GET['url']);

$routes->get('/Project','Project@index');
$routes->get('/Project/test','Project@show');
$routes->run();

projectDefine::set('ROUTER',$routes);



require_once('../Core/autoEnd.php');
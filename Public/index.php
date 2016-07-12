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

$Admin = new \Core\Router\Router($_GET['url'],'Admin');
$routes = new \Core\Router\Router($_GET['url']);

$routes->get('/','Project@index');

$routes->get('/Project','Project@index');
//die(var_dump(get_class($routes)));
$routes->prefix('test')->get('/Project/test','Project@bla');
$routes->get('/Project/test','Project@show');



$Admin->get('/Post','Category@index');
$Admin->get('/Post/test','Category@show');
$Admin->get('/Post/bla','Category@bla');


//$routes->group('Admin',function(\Core\Router\Router $router){
//   $router->get('/Category','Category@index');
//   $router->get('/Category/test','Category@show');
//   $router->get('/Category/boris','Category@bla');
//    return $router;
//});
$routes->group('Admin',$Admin);

//Debug::die_show($routes->showRoutes());

$routes->run();




projectDefine::set('ROUTER',$routes);



require_once('../Core/autoEnd.php');
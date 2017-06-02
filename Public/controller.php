<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

use Core\Magic\Debug\Debug;
use Core\Magic\Variables\projectDefine\projectDefine;
use Core\Magic\Variables\Server\Server;
use Core\Router\Router;
use Core\Router\src\routerException;

projectDefine::set('ROOT',dirname(__DIR__));

projectDefine::set('regex','[a-zA-Z]+');

projectDefine::set('HTTP',"http://");
projectDefine::set('SITE',projectDefine::get('HTTP'). Server::get('SERVER_NAME'));

$site_data['BASE'] = projectDefine::get('SITE') . 'Assets/';
$site_data = array(
    'JS'=>$site_data['BASE'].'js',
    'CSS'=>$site_data['BASE'].'css',
    'PLUGIN'=>$site_data['BASE'].'plugin',
    'IMG'=>$site_data['BASE'].'img',
);
projectDefine::set('SITE-DATA',$site_data);


$Routes = new Router(projectDefine::get('thisRoot'));



$Routes->get('/','index@index');
$Routes->get('/:name','index@param')->with('name',projectDefine::get('regex'));

try{
    $Routes->run();
}catch(routerException $e){
    Debug::show($e->getMessage());

}catch(Exception $e){
    Debug::show($e->getMessage());
}

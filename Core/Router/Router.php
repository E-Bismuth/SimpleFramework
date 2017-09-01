<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 12:42
 */

namespace Core\Router;


use Core\Magic\Variables\projectDefine\projectDefine;
use Core\Magic\Variables\Server\Server;
use Core\Router\src\routerException;

/** Url Routing
 * Class Router
 * @package Core\Router
 */
class Router
{
    /**Save the url typed
     * @var
     */
    private $url;
    /**All the routes of the project
     * @var array
     */
    private $routes = [];
    /**All the routes that have a name
     * @var array
     */
    private $namedRoutes = [];

    /**Prefix for routes
     * @var string
     */
    private $prefix = '';

    /**Make prefix fix for routes
     * @var bool
     */
    private $RewritePrefix = true;

    /**Get the url
     * Router constructor.
     * @param $url
     */
    public function __construct($url,$fixPrefix = null){
        if($fixPrefix != null){
            $this->unRewritePrefix();
            $this->prefix($fixPrefix);
        }
        $this->url = $url;
    }

    /**Called page by get method
     * @param $path
     * @param $callable
     * @param null $name
     * @return Route
     */
    public function get($path, $callable, $name = null){
        return $this->add($path,$callable,$name,'GET');
    }

    /**Called page by post method
     * @param $path
     * @param $callable
     * @param null $name
     * @return Route
     */
    public function post($path, $callable, $name = null){
        return $this->add($path,$callable,$name,'POST');
    }

    /**Save the route and save the name if given
     * INFORMATION!! = on $callable can give a function or a name of controller with the method to call
     * @param $path
     * @param $callable
     * @param $name
     * @param $method
     * @return Route
     */
    protected function add($path, $callable, $name, $method){

        if($this->prefix != ''){
            $path = substr($this->prefix,0,-1) . $path;
        }

        $route = $this->singleRouteInstance($path,$callable);

        if((is_string($callable)) && $name === null){
            $this->namedRoutes[$callable] = $route;
            $route->setName($callable);
        }
        else if($name){
            $this->namedRoutes[$name] = $route;
            $route->setName($name);

        }
        $this->routes[$method][]=$route;

        if($this->RewritePrefix)
            $this->prefix = '';


        return $route;
    }

    /**Return an instance of Route (separate in order to easily change the route instance)
     * @param $path
     * @param $callable
     * @return Route
     */
    protected function singleRouteInstance($path, $callable){
        return new Route($path,$callable);
    }

    /**Set prefix
     * @param $prefix
     * @return $this
     */
    public function prefix($prefix){
        $this->prefix = $prefix . '/';
        return $this;
    }

    /**Block the prefix for the rest of routes
     *
     */
    public function unRewritePrefix(){
        $this->RewritePrefix = false;
    }

    /** Group a list of routes
     * @param $prefix
     * @param null $callback
     * @return bool|Router
     * @throws routerException
     */
    public function group($prefix, $callback = null){


        if($prefix == '')
            throw new routerException('Can\'t create a group without prefix');

        if($callback instanceof \Core\Router\Router){
            $newGroup = $callback;
        }
        else if(get_class($callback) == 'Closure'){
            $newRouter = new Router($this->url);
            $newRouter->unRewritePrefix();
            $newRouter->prefix($prefix);

            $newGroup = call_user_func($callback, $newRouter);
        }
        else if($callback === null){
            return $this->prefix($prefix);
        }
        else{
            throw new routerException('Parameter must be a function or Core\Router\Router object');
        }



        foreach ($newGroup->showRoutes() AS $Method=>$newMethodedRoutes){
            foreach ($newMethodedRoutes as $newRoute) {
                $this->routes[$Method][] = $newRoute;
            }

        }
        foreach ($newGroup->showNamed() AS $newNamedRoutes){
                $this->namedRoutes[$newNamedRoutes->getName()] = $newNamedRoutes;
        }
        return false;
    }

    /**Find the Route
     * @return mixed
     * @throws routerException
     */
    public function run(){
        projectDefine::set('ROUTER',$this);
        if(!isset($this->routes[Server::get('REQUEST_METHOD')])){
            throw new routerException('REQUEST_METHOD does not exist');
        }
        foreach ($this->routes[Server::get('REQUEST_METHOD')] as $route) {
            if($route->match($this->url)){
                projectDefine::set('ACTUAL_ROOT',$route->getName());
                return $route->call();
            }
        }
        throw new routerException('No matching route');
    }

    /**Show all the route (good for debugging)
     * @return array
     */
    public function showRoutes(){
        return $this->routes;
    }

    /**Show all the Named route (good for debugging)
     * @return array
     */
    public function showNamed(){
        return $this->namedRoutes;
    }

    /**Build Url by routes
     * @param $name
     * @param array $params
     * @return mixed
     * @throws routerException
     */
    public function url($name, $params = []){
        if(!isset($this->namedRoutes[$name])){
            throw new routerException('No matching route named');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

    /**Build SiteMap
     * @param $name
     * @param array $params
     * @return mixed
     * @throws routerException
     */
    public function siteMap(){
        $siteMap = [];
        foreach ($this->namedRoutes AS $route){
            if($route->getIndex() !== false){
                $siteMap[] = [
                    'Url'=>$route->getUrl([]),
                    'Index'=>$route->getIndex()
                ];
            }
        }
        return $siteMap;
    }

}
<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 12:42
 */

namespace Core\Router;


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

    /**Get the url
     * Router constructor.
     * @param $url
     */
    public function __construct($url){
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
        $route = new Route($path,$callable);
        if((is_string($callable)) && $name === null){
            $this->namedRoutes[$callable] = $route;
            $route->setName($callable);
        }
        else if($name){
            $this->namedRoutes[$name] = $route;
            $route->setName($name);

        }
        $this->routes[$method][]=$route;
        return $route;
    }

    /**Find the Route
     * @return mixed
     * @throws routerException
     */
    public function run(){
        if(!isset($this->routes[Server::get('REQUEST_METHOD')])){
            throw new routerException('REQUEST_METHOD does not exist');
        }
        foreach ($this->routes[Server::get('REQUEST_METHOD')] as $route) {
            if($route->match($this->url)){
                return $route->call();
            }
        }
        throw new routerException('No matching route');
    }

    /**Show all the route (good for debugging)
     * @return array
     */
    public function show(){
        return $this->routes;
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

}
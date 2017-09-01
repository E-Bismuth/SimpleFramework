<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 12:45
 */


namespace Core\Router;


/**Define a single route
 * Class Route
 * @package Core\Router
 */
class Route
{


    /**Namespace of the app controllers
     * INFORMATION !! = can be overrated
     * @var string
     */
    protected $appControllerPath = "App\\Controller\\";

    /**How the controller in app are ending
     * INFORMATION !! = can be overrated
     * @var string
     */
    protected $appControllerExt = "Controller";

    /**Define the extraction code between name of controller and method
     * @var string
     */
    protected $appControllerExtractor = "@";

    /**Url
     * @var
     */
    private $path;
    /**Name of the route
     * @var
     */
    private $name;
    /**Method that will be execute if this route match
     * @var
     */
    private $callable;
    /**Param that could be passe on url
     * @var array
     */
    private $matches = [];
    /**Verification of route matching by regex
     * @var array
     */
    private $params = [];
    /**indexef for sitemap
     * @var array
     */
    private $sitemap = true;

    /**
     * Route constructor.
     * @param $path
     * @param $callable
     */
    public function __construct($path, $callable){

        $this->path = trim($path,'/');
        $this->callable = $callable;
    }

    /**Proceed the matching
     * @param $url
     * @return bool
     */
    public function match($url){
        $url = trim($url,'/');
        $path = preg_replace_callback('#:([\w]+)#',[$this,'paramMatch'],$this->path);
        $regex = "#^$path$#iu";
        if(!preg_match($regex,$url,$matches)){
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /** set Name of the Route
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }

    /**Found matches
     * @param $matches
     * @return string
     */
    private function paramMatch($matches){
        if(isset($this->params[$matches[1]])){
            return '('.$this->params[$matches[1]].')';
        }
        return '([^/]+)';
    }

    /** Call the function pass on routing or call the Controller
     * @return mixed
     */
    public function call(){
        if(is_string($this->callable)){
            $params = explode($this->appControllerExtractor,$this->callable);
            $controller = $this->appControllerPath .$params[0].$this->appControllerExt;
            $class = new $controller();
            $method = $params[1];

            return call_user_func_array([$class,$method],$this->matches);

        }
        else{
            return call_user_func_array($this->callable,$this->matches);
        }
    }

    /**Regex for complicate routing containing params
     * @param $param
     * @param $regex
     * @return $this
     */
    public function with($param, $regex){
        $this->params[$param] = str_replace('(','(?:',$regex);
        return $this;
    }

    /**Build Url by route
     * @param $params
     * @return mixed
     */
    public function getUrl($params){
        $path = $this->path;
        foreach ($params as $k=>$v){
            $path = str_replace(":$k",$v,$path);
        }
        return $path;
    }

    /**not index for sitemap
     * @return $this
     */
    public function notIndex(){
        $this->sitemap = false;
        return $this;
    }

    /**index strong for sitemap
     * @return $this
     */
    public function index($value = 0){
        if(is_int($value)){
            $this->sitemap = $value;
        }
        return $this;
    }

    /**not index for sitemap
     * @return $this
     */
    public function getIndex(){
        return $this->sitemap;
    }
}
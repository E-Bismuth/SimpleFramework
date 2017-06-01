<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 01/06/2017
 * Time: 14:59
 */

namespace App\Controller;


use Core\Controller\Controller;
use Core\Helpers\Header;
use Core\Magic\Variables\projectDefine\projectDefine;

class baseController extends Controller
{
    protected $js = [];
    protected $css = [];

    public function __construct() {
        $this->viewPath = projectDefine::get('ROOT').'/App/View/';
        $this->templatePath = projectDefine::get('ROOT').'/App/Template/';
        $this->templateName = 'bootstrap/';
    }

    protected function render($view, $variables = [],$js = [],$css = [])
    {
        if(!empty($js)){
            foreach ($js AS $item){
                $this->js[]=$item;
            }
        }
        if(!empty($css)){
            foreach ($css AS $item){
                $this->css[]=$item;
            }
        }
        
        if(!is_array($variables)){
            $variables = [$variables];
        }

        parent::render($view, $variables, $this->js, $this->css);
        exit();
    }

    protected function json($variables = [])
    {
        Header::contentJson();
        print_r(json_encode($variables));
        exit();
    }
}
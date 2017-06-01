<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 22/11/2016
 * Time: 08:37
 */

namespace Core\Views;


use Core\Magic\Debug\Debug;
use Core\Magic\Variables\Session\Flash;
use Core\Magic\Variables\viewVars\viewVars;

abstract class ViewsRender
{
    /**Folder that contain all views
     * @var
     */
    protected $viewPath;
    /**Extension of Views
     * @var string
     */
    protected $viewExt = 'php';

    /**Define the extraction code between name of folder and file
     * @var string
     */
    protected $appViewExtractor = "#";

    /**Folder that contains templates
     * @var
     */
    protected $templatePath;
    /**Name of the template used
     * @var
     */
    protected $templateName;
    /**Enter point file of the template
     * @var string
     */
    protected $templateEnterPoint = 'index';
    /**Extension of the template enter point file
     * @var string
     */
    protected $templateExt = 'php';

    /** Charge the view and send it on public
     * @param $view
     * @param array $variables
     */
    protected function render($view, $variables = [], $js = [], $css = []){
        if(count($variables)!=0){
            foreach ($variables AS $key=>$variable){
                viewVars::set('Datas/'.$key,$variable);
            }
        }
        ob_start();
        require($this->viewPath . str_replace($this->appViewExtractor,'/',$view) . '.' . $this->viewExt);
        $content = ob_get_clean();
        viewVars::set('Content',$content);
        require($this->templatePath . $this->templateName . $this->templateEnterPoint . '.' . $this->templateExt);

        Flash::transfer();
    }

}
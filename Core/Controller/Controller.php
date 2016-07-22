<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 14:51
 */

namespace Core\Controller;


/**
 * Class Controller
 * @package Core\Controller
 */
abstract class Controller
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

        ob_start();
        extract($variables);
        require($this->viewPath . str_replace($this->appViewExtractor,'/',$view) . '.' . $this->viewExt);
        $content = ob_get_clean();
        require($this->templatePath . $this->templateName . $this->templateEnterPoint . '.' . $this->templateExt);

    }

}
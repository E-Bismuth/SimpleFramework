<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 14:56
 */

namespace App\Controller;



use Core\Magic\Variables\projectVars\projectVars;

class ProjectController extends appController
{
    private $ProjectModel;

    public function __construct(){
        parent::__construct();
        $this->ProjectModel = new \App\Model\Project();
    }

    public function index(){
        $projects = $this->ProjectModel
            ->fields('Name')
            ->group('left')
            ->select()->get();

        $this->render('Project#index',compact('projects'));
    }
    public function show(){
        $projects = $this->ProjectModel->select()->get();


        $this->render('Project#show',compact('projects'));
    }

}
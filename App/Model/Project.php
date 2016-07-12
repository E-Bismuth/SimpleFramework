<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 04/07/2016
 * Time: 15:14
 */

namespace App\Model;

use App\Database\Localhost;
use Core\Model\Model;

class Project extends Model
{

    public function __construct()
    {
        parent::__construct(new Localhost());
    }

    public function group($direction, callable $callable = null){
        return $this->join($direction, new Group() ,$callable);
    }

    protected function Relations()
    {
        return array();
    }

}
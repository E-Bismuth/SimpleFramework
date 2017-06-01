<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 01/06/2017
 * Time: 15:31
 */

namespace App\Model;

use Core\Model\Model;

class baseModel extends Model
{

    /**Method that must be declare on child class that give the relation that can be made
     * between tables
     * exemple : array('users'=>array('users_id','id'))
     * @return array
     */
    protected function Relations()
    {
        return [];
    }
    public function __construct()
    {
        parent::__construct( new \App\DB\Test() );
    }
}
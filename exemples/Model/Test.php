<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 15/07/2016
 * Time: 10:04
 */

namespace exemples\Model;


use Core\Model\Model;

class Test extends Model
{
    
    public function __construct()
    {
        parent::__construct(new Localhost());
    }

    /**Method that must be declare on child class that give the relation that can be made
     * between tables
     * exemple : array('users'=>array('users_id','id'))
     * @return array
     */
    protected function Relations()
    {
        return array(
            'tests'=>['test2_id','id']
        );
    }

    public function Test2($direction, callable $callback = null){
        return $this->join($direction,new Test2(),$callback);
    }
}
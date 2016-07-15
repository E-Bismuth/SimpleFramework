<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 15/07/2016
 * Time: 10:04
 */

namespace exemples\Model;


use Core\Model\Model;

class Test2 extends Model
{
    
    protected $table = 'test2';
    
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
            'test2'=>['id','test2_id']
        );
    }
}
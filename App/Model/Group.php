<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 08/07/2016
 * Time: 10:50
 */

namespace App\Model;


use App\Database\Localhost;
use Core\Model\Model;

class Group extends Model
{

    public function __construct()
    {
        parent::__construct(new Localhost());
    }
    protected function Relations()
    {
        // TODO: Implement Relations() method.
    }
}
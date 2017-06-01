<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 01/06/2017
 * Time: 15:26
 */
namespace App\DB;

use Core\Model\SPDO;

class Test extends SPDO
{
    protected $SQL_MASTER_HOST = 'localhost';
    protected $SQL_MASTER_USER = 'root';
    protected $SQL_DTB = 'test';

}
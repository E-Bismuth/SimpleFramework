<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 07/07/2016
 * Time: 15:40
 */

namespace App\Database;


use Core\Model\SPDO;

class Localhost extends SPDO
{
    protected $SQL_DTB = 'laravel';
    protected $SQL_REPL_HOST = 'localhost';
    protected $SQL_REPL_USER = 'root';
    protected $SQL_REPL_PASS = '';

}
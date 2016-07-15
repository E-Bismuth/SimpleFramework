<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 15/07/2016
 * Time: 10:23
 */

namespace exemples\Model;


class Test3 extends Test
{
    protected $needWhereOnUpdate = false;
    protected $needWhereOnDelete = false;
    protected $table = 'tests';

}
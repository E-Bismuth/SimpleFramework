<?php

require_once '../Core/autoload.php';
use Core\Magic\Variables\projectDefine\projectDefine;
use Core\Helpers\Request;

Request::init();

projectDefine::set('thisRoot','/');
require_once ('controller.php');

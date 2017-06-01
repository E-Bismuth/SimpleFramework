<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 01/06/2017
 * Time: 15:03
 */
use Core\Magic\Variables\projectDefine\projectDefine;
use Core\Magic\Variables\viewVars\viewVars;

require_once('header.php');
    ?>
    <body id="<?= projectDefine::get('ACTUAL_ROOT');?>" class="container">
        <?= viewVars::get('Content');?>
    </body>
    <?php
require_once('footer.php');
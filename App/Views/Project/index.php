<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 15:00
 */

?>
<ul>
    <?php foreach ($projects AS $project){?>
        <li><?= $project['Name'];?></li>
    <?php }?>
</ul>

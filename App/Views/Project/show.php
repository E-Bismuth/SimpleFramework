<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 16:35
 */
?>
<ul>
    <?php foreach ($projects AS $project){?>
        <li><?= $project['Description'];?></li>
    <?php }?>
</ul>

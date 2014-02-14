
<?php
function lMenu($m='index'){
    if(App::$method == $m)
        echo ' class="active" ';
}
?>
<ul class="topMenu">
    <li><a <?lMenu()?> href="<?php App::url()?>/">Home Page</a></li>
    <li><a <?lMenu('onecol')?> href="<?php App::url()?>/index/onecol">One Column</a></li>
    <li><a <?lMenu('twocol')?> href="<?php App::url()?>/index/twocol">Two Columns</a></li>
    <li><a <?lMenu('threecol')?> href="<?php App::url()?>/index/threecol">Three Columns</a></li>

    <?php if($this->user): ?>
        <li><a class="ajax" href="<?php App::url()?>/index/logout" data-post="">Log out</a></li>
        <li><a href="<?php App::url()?>/page/editPages">Edit</a></li>
    <?php else: ?>
        <li><a class="ajax" href="<?php App::url()?>/index/login" data-post="">Login</a></li>
    <?php endif; ?>

</ul>

<?php
$active = function ($arg){
    $realArg = App::$requestLine;
    if($realArg == $arg){
        return 'class="active_left_menu"';
    }
};

?>

<h4>Основное меню</h4>

<ul class="left-menu">
    <?php foreach($dataMenu as $menu): ?>
        <li><a <?=$active('index/docs/'.$menu['class'].'/'.$menu['link'])?> href="<?php echo URL?>/index/docs/<?php echo $menu['class'].'/'.$menu['link']?>"><?php echo $menu['title']?></a></li>
    <?php endforeach; ?>
</ul>

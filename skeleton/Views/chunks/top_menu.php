<?php
function lMenu($m='index'){
    if(App::$method == $m)
        echo ' class="active" ';
}
?>
<ul class="topMenu">
    <li><a <?php lMenu('index');?> href="<?php App::url()?>/">Главная</a></li>
    <li><a <?php lMenu('info');?> href="<?php App::url()?>/index/info">Информация</a></li>
    <li><a <?php lMenu('about');?> href="<?php App::url()?>/index/about">О нас</a></li>
    <li><a <?php lMenu('contacts');?> href="<?php App::url()?>/index/contacts">Контакты</a></li>
</ul>


<?php
function lMenu($m='index'){
    if(App::$method == $m)
        echo ' class="active" ';
}
?>
<ul class="topMenu">
    <li><a <?php lMenu('index');?> href="<?php App::url()?>/"> <?App::lang('topMenuMain',1)?> </a></li>
    <li><a <?php lMenu('info');?> href="<?php App::url()?>/index/info"><?App::lang('topMenuInfo',1)?></a></li>
    <li><a <?php lMenu('about');?> href="<?php App::url()?>/index/about"><?App::lang('topMenuAbout',1)?></a></li>
    <li><a <?php lMenu('contacts');?> href="<?php App::url()?>/index/contacts"><?App::lang('topMenuContacts',1)?></a></li>
</ul>


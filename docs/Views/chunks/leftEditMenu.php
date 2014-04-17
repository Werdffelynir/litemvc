<h4><?php echo $menuTitle; ?></h4>

<ul class="left-menu">
    <li><a href="<?php echo $urlNewRecord?>">Новая запись</a></li>
    <?php foreach($dataMenu as $menu): ?>
    <li><a href="<?php echo $urlLinks.$menu['link']?>"><?php echo $menu['title']?></a></li>
    <?php endforeach; ?>
</ul>
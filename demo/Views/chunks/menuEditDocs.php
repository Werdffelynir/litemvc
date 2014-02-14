<h4>Редактирование документации</h4>

<ul class="left-menu">
	<li><a href="<?php echo URL?>/page/editDocs">Назад (Новая запись)</a></li>
    <?php foreach($dataDocs as $docs): ?>
        <li><a href="<?php echo URL?>/page/editDocs/<?php echo $docs['class'].'/'.$docs['id']?>"><?php echo limitChars($docs['class'] .'::'. $docs['title'], 40);?></a></li>
    <?php endforeach; ?>
</ul>

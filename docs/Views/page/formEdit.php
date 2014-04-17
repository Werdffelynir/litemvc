
<div><?php echo App::flash('formEdit'); ?></div>

<form class="ajaxEditForm" action="<?php App::url();?>/page/save/page" method="post">
	<div class="form_edit">
		<p>
			<span class="label">Заголовок страницы</span>
			<input type="text" name="title" value="<?=$dataEdit['title']?>">
		</p>

		<p>
			<span class="label">Ссылка на страницу</span>
			<input type="text" name="link" value="<?=$dataEdit['link']?>">
		</p>

		<p>
			<textarea id="editor" name="text" ><?=$dataEdit['text']?></textarea>
		</p>

		<p>
			<input type="submit"  value="Сохранить">
			<input hidden="hidden" name="type" value="<?=$dataEdit['formType']?>">
			<input hidden="hidden" name="id" value="<?=$dataEdit['id']?>">
            <?php if($dataEdit['formType']=='db'):?>
            <a href="<?php App::url();?>/page/delete/pages/<?=$dataEdit['id']?>" onclick="return confirm('Вы подтверждаете удаление записи: <?=$dataEdit['title']?> ?');">Delete</a>
            <?php endif; ?>
        </p>

	</div>
</form>

<!-- Подключение визуального редактора -->
<!-- Подключение визуального редактора -->
<script type="text/javascript" src="<?php App::url("theme");?>/js/nicEdit.js"></script>
<script type="text/javascript">
    var urlTheme = "<?php App::url('theme')?>";
    bkLib.onDomLoaded(function () {
        new nicEditor({
            fullPanel : true,
            iconsPath : urlTheme+'/js/nicEditorIcons.gif',
            buttonList : ['fontSize','bold','italic','underline','strikeThrough','subscript','superscript','html','image', 'xhtml']
        }).panelInstance('editor');
    });

    function confirmDelete() {
        if (confirm("Вы подтверждаете удаление?")) {
            return true;
        } else {
            return false;
        }
    }


</script>


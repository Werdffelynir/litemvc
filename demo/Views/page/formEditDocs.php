
<div><?php echo App::flash('formEdit'); ?></div>

<form class="ajaxEditForm" action="<?php App::url();?>/page/save/docs" method="post">
	<div class="form_edit_docs">

        <select name="class" id="">
            <option value="App" <?php if($dataEdit['class'] == 'App') echo "selected"?> >App</option>
            <option value="Controller" <?php if($dataEdit['class'] == 'Controller') echo "selected"?> >Controller</option>
            <option value="Model" <?php if($dataEdit['class'] == 'Model') echo "selected"?> >Model</option>
            <option value="SimplePDO" <?php if($dataEdit['class'] == 'SimplePDO') echo "selected"?> >SimplePDO</option>
            <option value="Config" <?php if($dataEdit['class'] == 'Config') echo "selected"?> >Config</option>
        </select>
        <select name="type" id="">
            <option value="option" <?php if($dataEdit['type'] == 'option') echo "selected"?> >option</option>
            <option value="method" <?php if($dataEdit['type'] == 'method') echo "selected"?> >method</option>
            <option value="-" <?php if($dataEdit['type'] == '-') echo "selected"?> >-</option>
        </select>

        <p>
            <span class="label">Название</span>
            <input type="text" name="title" value="<?=$dataEdit['title']?>">
        </p>

        <p>
            <span class="label">Описание</span>
            <input type="text" name="info" value="<?=$dataEdit['info']?>">
        </p>

		<p>
			<textarea id="editor" name="text" ><?=$dataEdit['text']?></textarea>
		</p>

		<p>
			<input type="submit"  value="Сохранить">
			<input hidden="hidden" name="formType" value="<?=$dataEdit['formType']?>">
			<input hidden="hidden" name="id" value="<?=$dataEdit['id']?>">
            <?php if($dataEdit['formType']=='db'):?>
            <a href="<?php App::url();?>/page/delete/docs/<?=$dataEdit['id']?>" onclick="return confirm('Вы подтверждаете удаление записи: <?=$dataEdit['class'].'::'.$dataEdit['title']?> ?');">Delete</a>
            <?php endif; ?>
        </p>

	</div>
</form>





<!-- Подключение визуального редактора -->
<!-- Подключение визуального редактора -->
<script type="text/javascript" src="<?php App::url("theme");?>/js/nicEdit.js"></script>
<script type="text/javascript">
    /*
    var urlTheme = "<?php //App::url('theme')?>";
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

*/
</script>


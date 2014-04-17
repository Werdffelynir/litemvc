<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">
<html>
<head>
    <title>LiteMVC</title>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta data-url="<?php App::url(); ?>" data-url-theme="<?php App::url('theme'); ?>" />

	<link rel="stylesheet" type="text/css" href="<?php App::url('theme'); ?>/css/style_original.css" />
	<link rel="stylesheet" href="<?php App::url('theme'); ?>/css/ajaxSlimBox.css" />
	
    <script type="text/javascript" src="<?php App::url('theme'); ?>/js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="<?php App::url('theme'); ?>/js/ajaxSlimBox.js"></script>
    <script type="text/javascript" src="<?php App::url('theme'); ?>/js/scripts.js"></script>
</head>
<body>


<div id="page">

	<div id="header">
        <h1>liteMVC <small>framework</small></h1>
        <div class="lang"><?php $this->chunk("langBox");?></div>
	</div><!--END header-->

    <div id="topMenu">
        <?php $this->chunk("topMenu");?>
    </div><!--END header-->

	<div id="content" class="lite clear">
		<?php $this->renderTheme( 'OUT' ); ?>
	</div><!--END content-->

	<div id="footer">
        <p>Copyright &copy; 1560 &mdash; 2013 SunLight, Inc. <a href="http://werd.id1945.com/"> OL Werdffelynir</a>. All
            rights reserved.</p>

        <p>Работает на liteMVC Framework | <?php global $timeLoader;
            list($microtime, $sec) = explode(chr(32), microtime());
            echo 'Компиляция: ' . round(($sec + $microtime) - $timeLoader, 4) . ' секунд'; ?>
        </p>
	</div><!--END footer-->

</div><!--END page-->

</body>
</html>
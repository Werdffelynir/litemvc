<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">
<html>
<head>
    <title>LiteMVC</title>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta data-url="<?php App::url(); ?>" data-url-theme="<?php App::url('theme'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php App::url('theme'); ?>/css/styles.css" />

    <script type="text/javascript" src="<?php App::url('theme'); ?>/js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="<?php App::url('theme'); ?>/js/scripts.js"></script>
</head>
<body>

	<div id="page">

		<div id="header" class="lite clear">
	        <div class="first lite_10 logo_block">liteMVC <small>skeleton</small></div>
	        <div class="lite_2 lang_block"><?php $this->chunk("langBox");?></div>
		</div><!--END header-->


	    <div id="topMenu" class="lite clear">
	        <?php $this->chunk("topMenu");?>
	    </div><!--END topMenu-->


		<div id="content" class="lite clear">
			<?php $this->renderTheme( 'OUT' );?>
		</div><!--END content-->


		<div id="footer">
	        <p>Copyright &copy; &mdash; 2014 SunLight, Inc. <a href="http://werd.id1945.com/"> OL Werdffelynir</a>. All rights reserved.
                <?php global $timeLoader; list($microtime, $sec) = explode(chr(32), microtime());
                echo 'Was compiled per: ' . round(($sec + $microtime) - $timeLoader, 4) . ' sec.'; ?>
            </p>
		</div><!--END footer-->


	</div><!--END page-->

</body>
</html>
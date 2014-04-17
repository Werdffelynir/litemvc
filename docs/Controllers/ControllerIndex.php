<?php


class ControllerIndex extends Ctrl
{

    public function actions()
    {
        return array(
            'one'=>'Components/actions/one',
            'two'=>'Components/actions/two',
        );
    }

    /*
	 * Обявление метода after() родительского класса Controller
	 * который выполняеться после инициализации всех компонентов
	 * Може использываться для переназначения неких параметров.
	 */
    public function after()
    {
        parent::after();

        // К примеру тут может быть установлен иной основной вид,
        // который будет применим к даному контролеру
        #$this->view = 'main_two_column';

		// 
        $this->modelPages = Pages::model();

        // Выборка с БД данных для левого меню
        $dataMenu = $this->modelPages->menuListPages();

        // Локальная-методная установка чанков
        $this->setChunk('leftOne','chunks/menuMainDocs', array('dataMenu'=>$dataMenu));
        $this->setChunk('leftTwo','chunks/left_two');
        $this->setChunk('rightOne','chunks/right_one');
        $this->setChunk('rightTwo','chunks/right_two');
    }


    function actionLang()
    {
        // Установка языка
        App::initLang($this->urlArgs(1),true);
        App::redirect($_SERVER["HTTP_REFERER"]);
    }

    /**
     * Страница Пример HOME PAGE
     */
    function actionIndex()
    {
        $title = 'Установлен язык: '.App::$langCode;
		$content = '<p>Тестовая генерация 1000 обращений к масиву переводов.</p><br>';
		
		
		
		/*
		// SPEED TEST
		list($msecX,$secX)=explode(chr(32),microtime());
		$timeX=$secX+$msecX;
		//START
		
		$content.= '<ol>';
        for($i=0;$i<10000;$i++)
            $content.='<li>'.App::lang('title').'</li>';
        $content.= '<ol>';
        
        //END
        list($msecX,$secX)=explode(chr(32),microtime());
		$generateX = 'Генерация скрипта <b>'.round(($secX+$msecX)-$timeX,4).'</b> секунд';
		*/


        // вызов Views/main.php
	    $this->render('OUT', false,
            array(
                'title'=>$title,//." ".$generateX,
                'content'=>$content
            ));
    }

    /**
     * Страница Пример одно--колоночной страницы ONE COLUMN
     */
    function actionOnecol()
    {
        $title =  __CLASS__;
        $content = $this->partial('partials/actionsOne');

        $this->render('OUT', 'main_one_column',
        array(
            'title'=>$title,
            'content'=>$content
        ));
    }


    /**
     * Страница Пример двух-колоночной страницы TWO COLUMNS
     */
    function actionTwocol()
    {
        $title = 'Language file';
        $content = $this->partialLang('content/cms_about');

	    $this->render('OUT', 'main_two_column',
            array(
                'title'=>$title,
                'content'=>$content
            ));
    }

    /**
     * Страница Пример трех-колоночной страницы THREE COLUMNS
     */
    function actionThreecol()
    {
        $title = __CLASS__;
        $content = __METHOD__;

	    $this->render('OUT', 'main_three_column',
            array(
                'title'=>$title,
                'content'=>$content
            ));
    }

    /**
     * Страница вызов окна LOGIN
     */
    public function actionLogin()
    {
        // Формирование контента страницы
        // заголовок страницы
	    $title = 'Воход в систему';
        // пожключения вида формы авторизации
	    $content = $this->partial("index/login", array(
            'login'=>(isset($_POST['login']))?$_POST['login']:'',
            'password'=>(isset($_POST['password']))?$_POST['password']:'',
        ));

        // Авторизация пользователя.
        // использует Classes: Auth::identity()
        // Принимает с формы $_POST['login'], $_POST['password']
        if(!empty($_POST['login']))
        {
            $user = Users::model()->userAuth(htmlspecialchars(trim($_POST['login'])));

            if($user AND $user['pass'] == $_POST['password']){
                $publicUserInfo = array(
                    'login' => $user['login'],
                    'email' => $user['email'],
                    'name'  => $user['name']
                );

                Auth::identity($user['id'], $publicUserInfo);

                $title = 'Воход в систему состоялся';
                $content = '<div><p>Вы успешно авторезируваны как '.$user['name'].'.</p>
                            <p>Через 3 сек. страница будет автоматически перезагружена!</p></div>';

                $this->render('OUT', 'main', array('title'=>$title, 'content'=>$content)); // вызов Views/page/index.php

                App::redirect($_SERVER["HTTP_REFERER"], 3);
            }
        }

        if($this->isAjax()){
            echo $content;
        }else{
            // вывод, в основной шаблон
            $this->render('OUT', 'main', array('title'=>$title, 'content'=>$content)); // вызов Views/page/index.php
        }
    }


    /**
     * Страница Вызов окна выхода
     */
    public function actionLogout()
    {
        // удаляение дользователя с куков
        Auth::out();

        // Если хапрос через AJAX выводится собщение, иначе редирект
        if($this->isAjax()){
            echo '<br><div><p>Выход!</p></div>';

            // здесь можно воспользываться универсальным редиректом
            App::redirectForce('index/index');
        }else{
            App::redirect(App::$url);
        }
    }


    /**
     *
     */
    public function actionDocs()
    {
        // метод urlArgs() возвращает аргумент запроса, тоесть третюю часть controller/method/argument
        $classPages = strip_tags(trim($this->urlArgs(1)));
        $linkPages = strip_tags(trim($this->urlArgs(2)));

		$tagsR = array('&lt;p&gt;','&lt;/p&gt;','&lt;b&gt;','&lt;/b&gt;','&lt;br&gt;','&lt;br/&gt;','&lt;br /&gt;','&lt;pre&gt;','&lt;/pre&gt;','&lt;code&gt;','&lt;code class="c"&gt;','&lt;code class="cl"&gt;','&lt;/code&gt;');
		$tagsS = array('<p>','</p>','<b>','</b>','<br>','<br/>','<br />','<pre>','</pre>','<code>','<code class="c">','<code class="cl">','</code>');

        if(!empty($linkPages)){
            // Подключение моддели Pages метода queryLink
            $result = Pages::model()->queryLink($linkPages);
            $getOptionClass = Docs::model()->queryByClass($classPages, "AND type='option'");

	        if(!empty($getOptionClass)){
                $result['text'] .= "<h3 class='doc-group'>OPTIONS</h3>";
				foreach( $getOptionClass as $value ){
					$result['text'] .= '
						<div class="showerBlock"> 
						    <span class="doc-code-line">'.htmlspecialchars($value['title']).'</span> 
						    '.htmlspecialchars($value['info']);

					
					if(!empty($value['text'])){
						$textRenamed = str_replace( $tagsR, $tagsS, htmlspecialchars($value['text'], ENT_NOQUOTES, "UTF-8")); 
					$result['text'] .= '
						    <span class="doc-btn">Подробней</span>
						    <div class="toggle" style="display:none">
						    	'.$textRenamed.'
						    </div>';
					}
					
					$result['text'] .= '</div>';
				}
			}

			$getMethodClass = Docs::model()->queryByClass($classPages, "AND type='method'");
			if(!empty($getMethodClass)){
                $result['text'] .= "<h3 class='doc-group'>METHODS</h3>";
				foreach( $getMethodClass as $value ){
					$result['text'] .= '
						<div class="showerBlock"> 
						    <span class="doc-code-line">'.$value['title'].'</span> 
						    '.$value['info'];
						    
					if(!empty($value['text'])){
						$textRenamed = str_replace( $tagsR, $tagsS, htmlspecialchars($value['text'], ENT_NOQUOTES, "UTF-8")); 
					$result['text'] .= '
						    <span class="doc-btn">Подробней</span>
						    <div class="toggle" style="display:none">
						    	'.$textRenamed.'
						    </div>';
					}
					
					$result['text'] .= '</div>';
				}
			}

            $getMethodClass = Docs::model()->queryByClass($classPages, "AND type='-'");
            if(!empty($getMethodClass)){
                $result['text'] .= "<h3 class='doc-group'>Конфигурация</h3>";
                foreach( $getMethodClass as $value ){
                    $result['text'] .= '
						<div class="showerBlock">
						    <span class="doc-code-line">'.$value['title'].'</span> 
						    '.$value['info'];
				
					if(!empty($value['text'])){
						$textRenamed = str_replace( $tagsR, $tagsS, htmlspecialchars($value['text'], ENT_NOQUOTES, "UTF-8")); 
					//$addedText = str_replace();
					$result['text'] .= '
						    <span class="doc-btn">Подробней</span>
						    <div class="toggle" style="display:none">
						    	'.$textRenamed.'
						    </div>';
					}
					
					$result['text'] .= '</div>';
                }
            }

            $title = $result['title'];
            $content = $this->partial('page/index',
                array(
                    'result'=>$result,
                ));
        }else{
            $title = '';
            $content = '';
        }

        // вызов Views/main.php
        $this->render('OUT', false,
            array(
                'title'=>$title,
                'content'=>$content
            ));
    }


}
<?php


class ControllerPage extends Ctrl {

    private $modelPages = null;
    private $modelDocs = null;

    public function actions()
    {
        return array();
    }

    /**
     *
     */
    public function after()
    {
        parent::after();

        if(!$this->id)
            App::redirect(App::$url);

        // подключение модели, в начале работы контролера,
        // так как он понадобится не в одном методе.
        $this->modelPages = Pages::model();
        $this->modelDocs = Docs::model();

        // Выборка с БД данных для левых меню
        $dataPageMenu = $this->modelPages->menuListPages();
        $dataDocsMenu = $this->modelDocs->getDocsMenu();

        // Локальная-методная установка чанков. 
        // Принимает масств данных в структурированом виде для 
        // универсальности шаблона меню
        $this->setChunk('leftOne', 'chunks/leftEditMenu', array(
            'menuTitle'=>'Редактирование страниц',
            'urlNewRecord'=>App::$url.'/page/editPages/',
            'urlLinks'=>App::$url.'/page/editPages/',
            'dataMenu'=>$dataPageMenu,
        ));

        $this->setChunk('leftTwo', 'chunks/leftEditMenu', array(
            'menuTitle'=>'Редактирование классов',
            'urlNewRecord'=>App::$url.'/page/editDocs/',
            'urlLinks'=>App::$url.'/page/editDocs/',
            'dataMenu'=>$dataDocsMenu,
        ));

    }


    /**
     * Длинные строки запроса
     *  test.loc/litemvc/demo/page/editPage/link
     *  test.loc/litemvc/demo/page/editDocs/class/5
     */
    public function actionEditPages()
	{
		// определение link
        $toLink = strip_tags(trim($this->urlArgs()));

	    // Выборка записей или бинд переменных
		if(!empty($toLink)){
            $result = $this->modelPages->queryLink($toLink);
            $result['formType'] = 'db';
        }else{
            // бинд для переменных формы
	        $result = array('id'=>'','link'=>'','title'=>'','text'=>'','formType'=>'new');
        }
        
        $title = ($result['formType'] == 'db') ? 'Редактирование записей':'Новая запись';
		$data = $this->partial('page/formEdit', array('dataEdit' => $result));
		$this->render('OUT', 'main', array('title' => $title, 'content'=>$data));
	}


    /**
     *  test.loc/litemvc/demo/page/editDocs/class/5
     */
    public function actionEditDocs()
	{
        // тип или класс, как категория
        $argType = strip_tags(trim($this->urlArgs(1)));
        // link или id записи
        $argId = strip_tags(trim($this->urlArgs(2)));

		if(!empty($argType)){
			// Выборка с БД данных для левого меню
	        $dataDocs = $this->modelDocs->queryByClass($argType);

	        if(!empty($dataDocs)){
		        // Локальная-методная установка чанков, переназначение
		        $this->setChunk('leftTwo','chunks/menuEditDocs', array('dataDocs'=>$dataDocs));
	        }
		}
        

        if(!empty($argId)){
            $result = $this->modelDocs->getById($argId);
            $result['formType'] = 'db';
        }else{
            // бинд для переменных формы
            $result = array('id'=>'','class'=>'','type'=>'','title'=>'','info'=>'','text'=>'','page_id'=>'','formType'=>'new');
        }

        $title = ($result['formType'] == 'db') ? 'Редактирование записей':'Новая запись';
        $data = $this->partial('page/formEditDocs', array('dataEdit' => $result));
        $this->render('OUT', 'main', array('title' => $title, 'content'=>$data));

	}

    /**
     *
     *  test.loc/litemvc/demo/page/save/pages/link
     *  test.loc/litemvc/demo/page/save/docs/5
     */
    public function actionSave()
	{
        if(!isset($_POST)) App::redirect(App::$url.'/page/edit/pages');

        // получение аргументов с строки запроса
        $argTbl = strip_tags(trim($this->urlArgs(1)));
        $argLink = strip_tags(trim($this->urlArgs(2)));

        if($argTbl == 'page') {
		
	        if($_POST['type'] == 'db')
	            $result = $this->modelPages->updatePageLeftMenu($_POST, $_POST['id']);
	        elseif($_POST['type'] == 'new')
	            $result = $this->modelPages->insertPageLeftMenu($_POST);

	        if($result){
	            App::flash('formEdit', '<div class="flashMessage_success">Запись успешно сохранена.</div>');
	        }else{
	            App::flash('formEdit', '<div class="flashMessage_error">Возникла ошибка во время сохранения.</div>');
	        }
	        App::redirect(App::$url.'/page/editPages/'.$_POST['link']);
	        
	    }elseif($argTbl == 'docs'){
		    
	        if($_POST['formType'] == 'db')
	            $result = $this->modelDocs->updateDocs($_POST, $_POST['id']);
	        elseif($_POST['formType'] == 'new')
	            $result = $this->modelDocs->insertDocs($_POST);

	        if($result){
	            // Установка флеш сообщения
	            App::flash('formEdit', '<div class="flashMessage_success">Запись успешно сохранена.</div>');

	            // Определение последней записи, и последующая выборка для переадрисации
	            $docsLastId = $this->modelDocs->docsLastId();
	            $lestUpdate = $this->modelDocs->getById($docsLastId);

	            // переадрисация, на толькочто созданую запись
	            App::redirect(App::$url.'/page/editDocs/'.$lestUpdate['class'].'/'.$lestUpdate['id']);
	        }else{
	            App::flash('formEdit', '<div class="flashMessage_error">Возникла ошибка во время сохранения.</div>');
	            App::redirect(App::$url.'/page/editDocs/');
	        }
	    }else{
		    
	    }

	}

    /**
     *
     *  test.loc/litemvc/demo/page/delete/pages/link
     *  test.loc/litemvc/demo/page/delete/docs/5
     */
    public function actionDelete()
    {
        // получение аргументов с строки запроса
        $argTbl = strip_tags(trim($this->urlArgs(1)));
        $argLink = strip_tags(trim($this->urlArgs(2)));

        if($argTbl == 'pages'){
	        
	        if(empty($argLink))
	            App::redirect(App::$url.'/page/editPages/');

	        $result = $this->modelPages->deletePageLeftMenu($argLink);

	        if($result){
	            App::flash('formEdit', '<div class="flashMessage_success">Запись была успешно удалена.</div>');
	        }else{
	            App::flash('formEdit', '<div class="flashMessage_error">Возникла ошибка во время удаления.</div>');
	        }
	        
        }elseif($argTbl == 'docs'){

	        if(empty($argLink))
	            App::redirect(App::$url.'/page/editDocs/');

	        $result = $this->modelDocs->deleteDocs($argLink);

	        if($result){
	            App::flash('formEdit', '<div class="flashMessage_success">Запись была успешно удалена.</div>');
	        }else{
	            App::flash('formEdit', '<div class="flashMessage_error">Возникла ошибка во время удаления.</div>');

	        }
	        App::redirect(App::$url.'/page/editDocs/');
	        
        }
	    App::redirect(App::$url.'/page/editPages/');
        
    }

} // END Class controller
<?php


class Ctrl extends Controller
{
	// Изминение шаблона layout с свойства контролера
	#public $layout = 'template_nn';
	// Изминение основного вида (может быть несколько) с свойства контролера
	#public $view = 'template_nn';

    // Установка свойства для авторизации
    public $id = false;
    public $user = false;

    /* Метод отрабатываеться до выполнения  */
    function before()
    {
        // Установка языка
        App::initLang();
        
        // Подключения класса авторизации Classes/Auth.php
        // Осуществляет проверку авоторизации, сохраненной в сесии
        Auth::run();
        $this->id = Auth::$id;
        $this->user = Auth::$user;

        $this->setChunk("topMenu", "chunks/topMenu");
        $this->setChunk("langBox", "chunks/langBox");
    }

    /**
     * Соотведственно отробатывается после запуска дополнительных частей
     * таких как файлов с каталога app/Classes/... или доугих подключеных
     * разработчиком
     */
    public function after()
    {
		/* Глобальное подключения, иного входного файла шаблона,
		 * может быть установленно в любому методе или как свойство клнтролера */
		#$this->layout = 'template_new';
		
		/* Глобальное подключения, иного основного вида,
		 * может быть установленно в любому методе или как свойство клнтролера */
		#$this->view = 'main_new';
	}

}
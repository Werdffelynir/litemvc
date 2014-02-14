<?php

// устанавка переменной с началом отсчета отработки системы
list($microtime, $sec) = explode(chr(32), microtime());
$timeLoader = $sec + $microtime;

// отображение всех ошибок
ini_set("display_errors",1);
error_reporting(E_ALL);


/**  *************************************************************************************
	Конфигурационные настройки приложения
	*************************************************************************************  */
	
$config = array(

    /**Каталог преложения */
    'appPath' => 'demo',

    /**Отладочный режим */
    'debug' => true,

    /**Название преложения */
    'appTitle' => 'My web application',

    /**Копирайт преложения */
    'appCopy' => 'Werdffelynir',

    /** Установить Язык */
    'language' => 'ru',

    /** Определять язык клиента и устанавить его по умолчанию */
    'identifyLanguage' => true,

    /**Файл вхождения для шаблона */
    'layout' => 'template',

    /**Файл вхождения для вида */
    'view' => 'main',

    /** реРоутинг, корекция видимых ссылок.
     * Работает на основе регулярных выражений, расход ресурсов незначительно увеличиваеться.
     * ключ [то что хотим видеть]
     * значение [реальный контроллер метод в фреймворке что вызывается (контролер/метод/парам)] */
    'reRouter' => array(
        // URL:login => controller:index method:login
        #'login' => 'index/login',
        // URL:page/154 => controller:page method:blog $this->param[1]:154
        #'page/(\d+)' => 'page/blog',
    ),

    /** Установка времени жизник кук */
    'cookieLife' => 3600 * 24,

    /** Подключение к БД */
    "dbConnects" => array(

        /* Настройки подключения к базе данных. через PDO SQLite */
        "db" => array(
            "driver" => "sqlite",
            "path" => "./DataBase/database.db",
        ),

		/* Настройки подключения к базе данных. через PDO Oracle 
        "kolo" => array(
            "driver" => "oci",
            "dbname" => "//dev.mysite.com:1521/orcl.mysite.com;charset=UTF8",
            "user"=>"user",
            "password"=>"password"
        ),*/
        
        /* Настройки подключения к базе данных. через PDO MySQL
        "dbMySql" => array(
		    "driver"    => "mysql",
		    "host"      => "localhost",
		    "dbname"    => "db",
		    "user"      => "root",
		    "password"  => "",
		),*/
    ),
	
);



/**  *************************************************************************************
	Запуск системы  
	*************************************************************************************  */

// Рабочие константы
define('DS', DIRECTORY_SEPARATOR);
define('APP', __DIR__.DS);
define('ROOT', dirname(__DIR__).DS);
define('LAYOUT', APP.'Views'.DS.'layout'.DS);

include( ROOT.'lib'.DS.'App.php' );

// Инициализация основного класса системы
$App = new App();
$App->getConfig($config);

// Подключение вайла для пользовательских функций.
if(file_exists(APP.'functions.php'))
	include( APP.'functions.php' );

// Пуск
$App->run();
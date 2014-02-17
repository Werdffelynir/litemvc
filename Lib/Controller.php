<?php
	
class Controller
{
    /**
	 * Активный файл основного шаблона входящего потока
     * @var string $layout
     */
    public $layout = null;


    /**
	 * Активный файл вида
     * @var string $view
     */
    public $view = null;


    /**
     * Свойство передачи в вид или шаблон части вида
     * Работет совместно с методом setChunk()
     */
    public $chunk;


	public function __construct()
	{
        $this->before();
        
        if($this->layout == null)
        	$this->layout = App::$config['layout'];
        	
        if($this->view == null)
        	$this->view = App::$config['view'];
/*
        if(App::getCookie('lang') != null){
            self::$langCode = App::getCookie('lang');
        }elseif(isset(App::$config['language']) AND self::$langCode == null){
            self::$langCode = App::$config['language'];
        }
*/

        $this->after();

        // Методы относящиеся к категории использующих настройки.
        //$this->initLang();
    }

	/**
	 * Выполняеться до загрузки основного вида и конфигурация в контролер
	 */
	public function before() {}

	/**
	 * Выполняеться после загрузки основного вида и конфигурация в контролер
	 */
	public function after() { }

	/**
	 * Упрощения для AJAX запросов
	 * <pre>
	 * // Пример:
	 * return array(
	 * 		'gate' => 'Components/itemGet',				// go to Components/itemGet.php
	 * 		'edit'=> 'Controllers/Post/UpdateAction',	// go to Controllers/Post/UpdateAction.php
	 * 	);
	 * </pre>
	 * @return array
	 */
	public function actions(){
        return array();
    }


    /**
     * Метод render() реализовывает подключение в основной шаблон theme видов с контролера
	 *
     * @param array|string  $dataPos    если array многовыводный режим, строка одна позиция позиция
     * @param bool|string   $view       если одиночный вид
     * @param array         $dataArr    если одиночный данные в виде массива
     */
    public function render( $dataPos, $view=false, array $dataArr=array() )
    {
        if(is_array($dataPos))
        {
            foreach ($dataPos as $keyBlockName => $dataView) {
                if(isset($dataView[1]) AND is_array($dataView[1]))
                    extract($dataView[1]);
                $keyInclude = './Views/'.$dataView[0].".php";
                ob_start();
                include $keyInclude;
                $this->$keyBlockName = ob_get_clean();
            }
        }
        elseif(is_string($dataPos))
        {
            if(!$view) $view = $this->view;
            if(!empty($dataArr)) extract($dataArr);
            $keyInclude = './Views/'.$view.".php";
            ob_start();
            include $keyInclude;
            $this->$dataPos = ob_get_clean();
        }
        
        include './Views/layout/'.$this->layout.".php";

    }

	
    /**
     * Метод вывода в шаблон видов с данными, совметсный с методом render()
     *
     * @param string    $renderPosition     названеи позиции указаной в контролере методом render()
     */
    public function renderTheme( $renderPosition )
    {
        if(isset($this->$renderPosition))
            echo $this->$renderPosition;
    }


    /**
     * Обработка в указаном виде, переданых данных, результат возвращает.
     *
     *<pre>
     * Пример:
     * $content = $this->partial("blog/topSidebar", array( "var" => "value" ));
     *</pre>
     *
     * @param   string  $viewName   путь к виду, особености:
     *                              	"partial/myview" сгенерирует "app/Views/partial/myview.php"
     *                              	"//partial/myview" сгенерирует "app/partial/myview.php"
     * @param   array   $data       массив данных для передачи в вид
     * @param   bool    $e
     * @return null|string
     */
  public function partial( $viewName, array $data=null, $e=false )
    {

        if(empty($viewName)) {
            return null;
        }elseif(substr($viewName, 0, 2) == '//'){
			$viewName = substr($viewName, 2);
	        //$toInclude = ROOT.App::$config['appPath'].DS.$viewName.'.php';
	        $toInclude = $viewName.'.php';
        }else{
	        //$toInclude = ROOT.App::$config['appPath'].DS.'Views'.DS.$viewName.'.php';
	        $toInclude = 'Views'.DS.$viewName.'.php';
        }
        
        if($data != null)
        	extract($data);
        	
        if(!is_file($toInclude)){
            if(App::$debug){
                App::ExceptionError('ERROR! File not exists!', $toInclude);
            } else {
            	return null;
            }
        }
            
        ob_start();
        include $toInclude;
        $getContents = ob_get_contents();
        ob_clean();

		if($e)
        	echo $getContents;
		else
        	return $getContents;
    }

    /**
     * Достать перевод документ
     */
    public static function partialLang($file, array $data=null, $e=false)
    {
		if(substr($file, 0, 2) == '//'){
			$file = substr($file, 2);
	        //$toInclude = ROOT.App::$config['appPath'].DS.$file.'.php';
	        $toInclude = $file.'_'.App::$langCode.'.php';
        }else{
	        //$toInclude = App::$appPath.'Languages'.DS.$file.'_'.App::$langCode.'.php';
	        $toInclude = 'Languages'.DS.$file.'_'.App::$langCode.'.php';
        }

        if(!is_file($toInclude))
            if(App::$debug)
                App::ExceptionError('ERROR! File not exists!', $toInclude);
            else return null;

        ob_start();

        if($data != null)
        	extract($data);
        	
        include $toInclude;
        $getContents = ob_get_contents();
        ob_clean();

		if($e)
        	echo $getContents;
		else
        	return $getContents;
    }


    /**
     *
     * Обработка в указаном виде, переданых данных, результат будет передан в основной вид или тему по указаному $chunkName,
     * также есть возможность вернуть результат в переменную указав четвертый параметр в true.
     *
     *<pre>
     * Пример:
     * $this->setChunk("topSidebar", "blog/topSidebar", array( "var" => "value" ));
     *
     * в шаблон blog/topSidebar.php передаеться переменная $var с значением  "value".
     *
     * В необходимом месте основного вида или темы нужно обявить чанк
     * напрямую:
     * echo $this->chunk["topSidebar"];
     * или методом:
     * $this->chunk("topSidebar");
     *</pre>
     *
     * @param string    $chunkName  зарегестрированое имя
     * @param string    $chunkView  путь у виду чанка, установки путей к виду имеют следующие особености:
     *                              	"partial/myview" сгенерирует "app/Views/partial/myview.php"
     *                              	"//partial/myview" сгенерирует "app/partial/myview.php"
     * @param array     $dataChunk  передача данных в вид чанка
     * @param bool      $returned   по умочнию FALSE производится подключения в шаблон, если этот параметр TRUE возвращает контент
     * @return string
     */
    public function setChunk( $chunkName, $chunkView='', array $dataChunk=null, $returned=false )
    {
        // Если $chunkView передан как пустая строка, создается заглушка
        if(empty($chunkView)) {
            return $this->chunk[$chunkName] = '';
        }elseif(substr($chunkView, 0, 2) == '//'){
			$chunkView = substr($chunkView, 2);
	        //$viewInclude = ROOT.App::$config['appPath'].DS.$chunkView.'.php';
	        $viewInclude = $chunkView.'.php';
        }else{
	        //$viewInclude = ROOT.App::$config['appPath'].DS.'Views'.DS.$chunkView.'.php';
	        $viewInclude = 'Views'.DS.$chunkView.'.php';
        }
        
        // Если вид чанка не существует отображается ошибка
        if(!file_exists($viewInclude)){
            if(App::$debug) {
                App::ExceptionError('ERROR! File not exists!', $viewInclude);
            } else {
            	return null;
            }
        }

        if(!empty($dataChunk)) 
        	extract($dataChunk);
        
        ob_start();
        ob_implicit_flush(false);
        include $viewInclude;
        
        if(!$returned)
            $this->chunk[$chunkName] = ob_get_clean();
        else
            return ob_get_clean();
    }


    /**
     * Вызов зарегестрированого чанка. Первый аргумент имя зарегестрированого чанка
     * второй тип возврата метода по умолчанию ECHO, если FALSE данные будет возвращены
     *
     * <pre>
     * Пример:
     *  $this->chunk("myChunk");
     * </pre>
     *
     * @param  string    $chunkName
     * @param  bool      $e
     * @return bool
     */
    public function chunk( $chunkName, $e=true )
    {
        if(isset($this->chunk[$chunkName])){
            if($e)
                echo $this->chunk[$chunkName];
            else
                return $this->chunk[$chunkName];
        }else{
            if(App::$debug)
                App::ExceptionError('ERROR Undefined chunk',$chunkName);
            else
                return null;
        }
    }


    /**
     * Метод для Вывода ошибки 404
     *
     * @param string    $file
     * @param array     $textData
     * @param bool      $e
     * @return string
     */
    public static function error404($file='layout/error404', array $textData=null, $e=true)
    {
	    $c = new self();
	    if($e)
	    	echo $c->partial($file, $textData);
	    else
        	return $c->partial($file, $textData);
    }


    /**
     * Метод для проверки являеться ли запрос AJAX
     * @return bool
     */
    public function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Метод использования передаваемых парметров через строку запросов.
     * основное предназначение это передача неких параметров, но все же
     * можно найти множество других приминений для этого метода.
     *
     * <pre>
     * Например: http://site.loc/edit/page/id/215/article/sun-light
     * /edit/page/ - это контролер и екшен, они пропускаються
     * $this->urlArgs()            - id возращает первый аргумент
     * $this->urlArgs(1)		   - id аналогично, но '1' != 1
     * $this->urlArgs(3)		   - article возращает третий аргумент
     * $this->urlArgs('id')        - 215
     * $this->urlArgs('article')   - sun-light
     * $this->urlArgs('getArray')  - масив всех елементов "Array ( [1] => edit [2] => page [3] => id [4] => 215 [5]..."
     * $this->urlArgs('getString') - строку всех елеметов "edit/page/id/215/article/sun-light"
     * $this->urlArgs('edit', 3)   - 215 (3 шага от 'edit')
     * </pre>
     * 
     * @param bool $param
     * @param int $element
     * @return array|string
     */
    public static function urlArgs($param=false, $element=1)
    {
        if(empty(App::$args)) return null;

		// отдает первый елемент
        if(!$param) {
            return App::$args[0];
            
        // отдает по номеру елемент
        }elseif( is_int($param) ){
	        $pNum = $param - 1;
            return (isset(App::$args[$pNum])) ? App::$args[$pNum] : null; 

        // отдает все елементы в массиве
        }elseif($param == 'getArray'){
            return App::$args;

        // отдает все елементы в строке
        }elseif($param == 'getString'){
            return App::$requestLine;

        // отдает елемент следующий после указаного 
        }else{
	        if(in_array($param, App::$args)){
	            if($element > 0){
		            $keyElement = array_search($param, App::$args);
		            $key = $keyElement+$element;
	                return (isset(App::$args[$key]))?App::$args[$key]:null;
	            } else {
	                return $param;
	            }
		    }else{
			    return null;
		    }
        }
    }



	/**
	 * Метод для подключение моделей, параметром берет созданый раньше класс Метода
	 * Возвращает обект модели с ресурсом подключеным к базе данных
	 *
	 * @param  string $modelName Имя класса модели
	 * @return bool|object
	 */
	public function model($modelName)
    {
        if (class_exists($modelName)) {
            $model = new $modelName();
            return (object) $model;                   
        }else{
            if(App::$debug)
                App::ExceptionError('ERROR model class not exists!',$modelName);
        }
    }


    /**
     *
     */
    public function initLang($langCode=false, $cookie=true)
	{
        App::initLang($langCode, $cookie);
	}

	/**
     * Доступк к переводу и остальным атрибутам
     */
	public function lang($key, $e=false)
    {
        return App::lang($key, $e);
	}


	
} // END CLASS 'Controller'
<?php

class ControllerIndex extends Ctrl {

    public function actions() {
        return array('one' => 'Components/actions/one', 'two' => 'Components/actions/two', );
    }

    /*
     * Обявление метода after() родительского класса Controller
     * который выполняеться после инициализации всех компонентов
     * Може использываться для переназначения неких параметров.
     */
    public function after() {
        parent::after();

        # Code...

        // Локальная-методная установка чанков,
        //$this->setChunk('rightOne','chunks/r_sidebar_info');
        //$this->setChunk('rightOne','chunks/r_sidebar_copy');
        //$this->setChunk('rightTwo','chunks/r_sidebar_banner');

    }

    function actionLang() {
        // Переключение языка и редирект
        App::initLang($this -> urlArgs(1), true);
        App::redirect($_SERVER["HTTP_REFERER"]);
    }

    /**
     * Обработчик страницы "Главная"
     */
    function actionIndex() {
        //$modelTable = Table::model();
        //$result = $modelTable->db->query('SELECT * FROM "table" LIMIT 50')->all();
        //$result = $modelTable->db->exec("INSERT INTO 'table' ('title', 'text') VALUES ('Title three','Text for record three')");
        //var_dump($result);

      //var_dump(App::$url);

        if (App::$langCode == 'en') {
            $content = '
          <h1>History of PHP</h1>
          <p>PHP as it\'s known today is actually the successor to a product named PHP/FI. Created in 1994 by Rasmus Lerdorf, the very first incarnation of PHP was a simple set of Common Gateway Interface (CGI) binaries written in the C programming language. Originally used for tracking visits to his online resume, he named the suite of scripts "Personal Home Page Tools," more frequently referenced as "PHP Tools." Over time, more functionality was desired, and Rasmus rewrote PHP Tools, producing a much larger and richer implementation. This new model was capable of database interaction and more, providing a framework upon which users could develop simple dynamic web applications such as guestbooks. In June of 1995, Rasmus » released the source code for PHP Tools to the public, which allowed developers to use it as they saw fit. This also permitted - and encouraged - users to provide fixes for bugs in the code, and to generally improve upon it.</p>
          <p>In September of that year, Rasmus expanded upon PHP and - for a short time - actually dropped the PHP name. Now referring to the tools as FI (short for "Forms Interpreter"), the new implementation included some of the basic functionality of PHP as we know it today. It had Perl-like variables, automatic interpretation of form variables, and HTML embedded syntax. The syntax itself was similar to that of Perl, albeit much more limited, simple, and somewhat inconsistent. In fact, to embed the code into an HTML file, developers had to use HTML comments. Though this method was not entirely well-received, FI continued to enjoy growth and acceptance as a CGI tool --- but still not quite as a language. However, this began to change the following month; in October, 1995, Rasmus released a complete rewrite of the code. Bringing back the PHP name, it was now (briefly) named "Personal Home Page Construction Kit," and was the first release to boast what was, at the time, considered an advanced scripting interface. The language was deliberately designed to resemble C in structure, making it an easy adoption for developers familiar with C, Perl, and similar languages. Having been thus far limited to UNIX and POSIX-compliant systems, the potential for a Windows NT implementation was being explored.</p>';
        } else {
            $content = '
          <h1>Критичные замечания по теме</h1>
          <p>Повседневная практика показывает, что начало повседневной работы по формированию позиции требует от нас анализа новейших вариантов поиска решений.</p>
          <p>Товарищи, начало повседневной работы по формированию позиции обеспечивает широкому  кругу (специалистов) участие в формировании системы массового участия.</p>
          <p>Таким  образом рамки и место обучения кадров создает все необходимые предпосылки для утверждения и анализу необходимых данных для разрешения ситуации в целом.</p>
          <p>Повседневная практика показывает, что сложившаяся ситуация ни коим образом не позволяет выполнить важные задания по разработке новых предложений.</p>';
        }

        // вывод в Views/main.php
        $this -> render('OUT', 'main', array('content' => $content));
    }

    /**
     * Обработчик страницы "О скилетроне"
     */
    function actionInfo() {
        $content = $this -> partialLang('content/info');

        // вывод в Views/main_one_column.php
        $this -> render('OUT', 'main_one_column', array('content' => $content));
    }

    /**
     * Обработчик страницы "О системе"
     */
    function actionAbout() {
        $content = $this -> partialLang('content/about');

        // вывод в Views/main.php
        $this -> render('OUT', 'main', array('content' => $content));
    }

    /**
     * Обработчик страницы "Контакты"
     */
    function actionContacts() {
        $content = $this -> partial('index/contacts');

        // вывод в Views/main.php
        $this -> render('OUT', 'main', array('content' => $content));
    }

    /**
     * Страница вызов окна LOGIN
     */
    public function actionLogin() {
        // Формирование контента страницы

        // подключения вида формы авторизации
        $content = $this -> partial("index/login", array('login' => (isset($_POST['login'])) ? $_POST['login'] : '', 'password' => (isset($_POST['password'])) ? $_POST['password'] : '', ));

        // Авторизация пользователя.
        // использует Classes: Auth::identity()
        // Принимает с формы $_POST['login'], $_POST['password']
        if (!empty($_POST['login'])) {
            //$user = Users::model()->userAuth(htmlspecialchars(trim($_POST['login'])));
            $user = array('id' => '159', 'login' => 'admin', 'pass' => 'admin', 'email' => 'admin@admin.com', 'name' => 'Administrator', );

            if ($user AND $user['pass'] == $_POST['password']) {
                $publicUserInfo = array('login' => $user['login'], 'email' => $user['email'], 'name' => $user['name']);

                Auth::identity($user['id'], $publicUserInfo);

                $content = '
                <div>
                	<h2>Воход в систему состоялся</h2>
                	<p>Вы вошли в систему как ' . $user['name'] . '.</p>
                	<p>Через 3 сек. страница будет автоматически перезагружена!</p>
                </div>
                ';

                $this -> render('OUT', 'main', array('content' => $content));

                App::redirect(App::$url . '/index', 3);
            }
        }

        // вывод, в основной шаблон
        $this -> render('OUT', 'main', array('content' => $content));

    }

    /**
     * Страница Вызов окна выхода
     */
    public function actionLogout() {
        // удаляение пользователя с сессии и редирект
        Auth::out();
        App::redirect(App::$url);
    }

}

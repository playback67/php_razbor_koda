<?php


namespace app\core; // объявляем пространство имен ядра


class Router // объявляем класс Роутер
{

    protected $routes = [];
    protected $params = [];

    public function __construct() // объявляем метод-конструктор
    {
        $arr = require 'app\config\routes.php'; // добавляем файл
        foreach ($arr as $key => $val) {
            $this->add($key, $val); // добавляем ключи?
        }
    }


    public function add($route, $params)
    {
        $route = '#^' . $route . '$#'; // не очень тут врубаюсь по синтаксису
        $this->routes[$route] = $params;
    }

    public function match()
    {
        $url = parse_url($_SERVER['REQUEST_URI']); // разбираем URL-адрес сервера на компоненты, возвращая их в виде массива
        $path = $url['path'];
        if (strlen($path) > 2) { // если длина строки >2
            $path = trim($url['path'], '/'); // удаляем пробелы из начала и конца строки
        } else $path = ''; // иначе путь пустой
        foreach ($this->routes as $route => $params) { // логику этого момента не очень понял, нужно разъяснение!!!!!
            if (preg_match($route, $path, $matches)) { // выполняем проверку на соответствие регулярному выражению
                $this->params = $params;
                array_push($this->params, $matches); // добавляем элемент в конец массива
                return true;
            }
        }
        return false;
    }

    public function run()
    {
        if ($this->match()) { // Выражение match предназначено для ветвления потока исполнения на основании проверки совпадения значения с заданным условием.
            $path = 'app\controllers\\' . ucfirst($this->params['controller']) . 'Controller'; // преобразуем первый символ в верхний регистр
            if (class_exists($path)) { // если класс существует, то... не понимаю, что
                $action = $this->params['action'] . 'Action';
                if (method_exists($path, $action)) { // если метод существует, то ...
                    $controller = new $path($this->params); // по сути, как я понимаю, мы проверяем наличие контроллеров, классов, методов и тд. Я прав?
                    $controller->$action();
                } else {
                    //Ошибка: не найден метод контроллера
                    View::errorCode(404);
                }
            } else {
                //Ошибка: не найден контроллер
                View::errorCode(404);
            }
        } else {
            //Ошибка 404... Либо редирект
            View::errorCode(404);
        }
    }
}
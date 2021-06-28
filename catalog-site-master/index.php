<?php

require 'app\lib\dev.php'; // вставляем файл

use app\config\Database; // подключаем БД
use app\core\Registry; // подключаем регистратор
use app\core\Router; // подключаем маршрутизатор

spl_autoload_register(function ($class) { // Регистрирует заданную функцию (класс) в качестве реализации метода __autoload(), которая пытается загрузить неопределенный класс
    $path = str_replace('\\', '/', $class . '.php'); //  Заменяет все вхождения строки поиска на строку замены
    if (file_exists($path)) {
        require $path;
    }
});

//session_start();

Registry::set('db', new Database()); // про это уже писал, что не понял

$router = new Router();
$router->run();
<?php


namespace app\core; // объявляем пространство имен ядра


class View // объявляем класс вьюшки
{
	public $path; // задаем переменные
	public $route;
	public $paginationUrl = "";
	public $activeProductPage = 0; // ставим активную страничку товара и категории 0
	public $activeCategoryPage = 0;
	public $layout = 'default'; // расположение - дефолт?

	public function __construct($route) // объявляем метод-конструктор
	{
		$this->route = $route; // какая-то работа с маршрутизаторами
		$this->path = $this->route['controller'] . '/' . $this->route['action'];


		if (strpos($_SERVER['REQUEST_URI'], 'category')){ // Возвращает позицию первого вхождения подстроки. ЛОГИКУ НЕ ПОНЯЛ
			$length = strpos($_SERVER['REQUEST_URI'], '/goods', 1); // Возвращает позицию первого вхождения подстроки (как это связано с переменной "длина"?)
			$substr = substr($_SERVER['REQUEST_URI'], 0, $length); // Возвращает подстроку
			$this->paginationUrl.=$substr; // нужны пояснения
		}


		/*$strWithoutPage = strrpos($_SERVER['REQUEST_URI'], '/page');

		if ($strWithoutPage > 0 && $strWithoutPage) {
			$this->paginationUrl = substr($_SERVER['REQUEST_URI'], 0, $strWithoutPage);
		} else if ($strWithoutPage === 0) {
			$this->paginationUrl ='';
		} else {
			$this->paginationUrl = $_SERVER['REQUEST_URI'];
		}*/

		if (!empty($this->paginationUrl)) { // строчки по отдельности я могу понять, а вот логику - нет :)
			if ($this->paginationUrl[strlen($this->paginationUrl) - 1] == '/') { // возвращает длину строки
				$this->paginationUrl = substr($this->paginationUrl, 0, -1); // Возвращает подстроку
			}
		}

		if (isset($this->route[0]['productPage'])) { // Функция isset() позволяет определить, инициализирована ли переменная или нет. Если переменная определена, то isset() возвращает значение true . 
			$this->activeProductPage = $this->route[0]['productPage']; // понимаю, что настраиваем страницу товаров, то с логикой реальные проблемы
		}
		if (isset($this->route[0]['categoryPage'])) {
			$this->activeCategoryPage = $this->route[0]['categoryPage'];
		}

	}

	public function render($title, $pathDirectory, $views = [], $vars = []) // что-то про рендер
	{
		extract($vars); // Импортирует переменные из массива в текущую таблицу символов

		if (file_exists('app/views/' . strtolower($pathDirectory))) { // если файл существует + символы преобразованы в нижний регистр

			foreach ($views as $viewName) { // для каждой вьюшки
				if (file_exists('app/views/' . strtolower($pathDirectory) . strtolower($viewName) . '.php')) { // если файл существует + символы преобразованы в нижний регистр
					ob_start(); //  Включение буферизации вывода. Буферизация — способ организации обмена, в частности, ввода и вывода данных в компьютерах и других вычислительных устройствах
					require 'app/views/' . strtolower($pathDirectory) . strtolower($viewName) . '.php'; // подключаем файл
					$content[strtolower($viewName)] = ob_get_clean();
				}
			}

			ob_start(); 
			require 'app/views/' . strtolower($pathDirectory) . 'index.php';
			$content['index'] = ob_get_clean();
		}
		require 'app/views/layouts/' . $this->layout . '.php';


	}

	public static function errorCode($code) // делаем функцию ошибочного кода
	{
		http_response_code($code); // Получает или устанавливает код ответа HTTP
		require 'app/views/errors/' . $code . '.php';
		exit();
	}

	public function redirect($url) // делаем редирект
	{
		header('Location: http://'.$_SERVER['HTTP_HOST']. $url); // Отправка HTTP-заголовка
		exit();
	}// над пониманием логики надо очень много работать
}
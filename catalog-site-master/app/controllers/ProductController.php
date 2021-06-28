<?php


namespace app\controllers; // создаем общее пространство имен для папки "контроллеры"


use app\core\Controller; // подключаем класс "Контроллер"

class ProductController extends Controller // создаем класс, который является дочерним от класса "Контроллер" (наследует все общедоступные и защищённые методы, свойства и константы родительского класса)
{

	private $productId;
	/*private $categories = [];*/

	public function __construct($route) // объявляем метод-конструктор
	{
		parent::__construct($route);
		if (isset($route)) {
			$this->productId = $route[0]['productId']; // задаем id товаров
		}

	}

	public function indexAction()
	{

		$model = $this->loadModel('Product'); // грузим модель
		$result['Product'] = $model['Product']->getProductById($this->productId)[0]; // фиксируем товары по id
		$result['Categories'] = $model['Product']->getCategoriesProductById($this->productId); // фиксируем категории по id

		$this->view->render($result['Product']['productName'], 'product/', [], $result); // рендерим товары
	}


}
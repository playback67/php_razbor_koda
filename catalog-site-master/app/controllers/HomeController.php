<?php


namespace app\controllers; // создаем общее пространство имен для папки "контроллеры"


use app\core\Controller; // подключаем класс "Контроллер"


class HomeController extends Controller // создаем класс, который является дочерним от класса "Контроллер" (наследует все общедоступные и защищённые методы, свойства и константы родительского класса)
{
	private $productPage = 0; // приравниваем значение переменной, отвечающей за товар/категорию, нулю
	private $categoryPage = 0;
	private const RECORD_PER_PAGE = 5; // ограничение на 5 записей на страницу???

	public function __construct($route) // объявляем метод-конструктор
	{
		parent::__construct($route);
		if (isset($route[0]['productPage'])) { // как я понимаю, вызываем страницу с товарами/категориями
			$this->productPage = $route[0]['productPage'];
		}
		if (isset($route[0]['categoryPage'])) {
			$this->categoryPage = $route[0]['categoryPage'];
		}

	}

	public function indexAction()
	{
		$startProduct = $this->productPage * self::RECORD_PER_PAGE; // выбрать все записи со страницы? не очень понял (для товара)
		$startCategory = $this->categoryPage * self::RECORD_PER_PAGE; // выбрать все записи со страницы? не очень понял (для категорий)

		$this->loadModel('Product'); // грузим товары и категории
		$this->loadModel('Category');

		$result['Products']['Page'] = ceil($this->model['Product']->countRecordInDatabase('goods') / self::RECORD_PER_PAGE); //считаем записи в БД?. Ceil округляет дробь в большую сторону. Связь запись-дата
		$result['Category']['Page'] = ceil($this->model['Category']->countRecordInDatabase('categories') / self::RECORD_PER_PAGE);


		$result['Products']['Data'] = $this->model['Product']->getProducts($startProduct, self::RECORD_PER_PAGE); // фиксируем товары
		$result['Category']['Data'] = $this->model['Category']->getCategories($startCategory, self::RECORD_PER_PAGE);

		$this->view->render("Home", 'home/', ['products', 'categories'], $result); // рендерим home-page?
	}

	public function getGoodsByCategoriesAction() // что есть гудс
	{
		$startCategory = $this->categoryPage * self::RECORD_PER_PAGE;
		$startProduct = $this->productPage * self::RECORD_PER_PAGE;

		//Загружаем модель
		$this->loadModel('Product');
		$this->loadModel('Category');

		$category = $this->route[0]['category'];

		/*$result['Products']['Page'] = ceil($this->model['Product']->countRecordInDatabase('goods') / self::RECORD_PER_PAGE);
		$result['Category']['Page'] = ceil($this->model['Category']->countRecordInDatabase('categories') / self::RECORD_PER_PAGE);*/

		$result['Products']['Data'] = $this->model['Product']->getGoodsByCategory($category, $startProduct, self::RECORD_PER_PAGE);
		$result['Products']['Category'] = $this->model['Category']->getCategoryByName($category)[0];
		$result['Category']['Data'] = $this->model['Category']->getCategories($startCategory, self::RECORD_PER_PAGE);

		$result['Products']['Page'] = ceil($this->model['Product']->getCountGoodsByCategory($category)/ self::RECORD_PER_PAGE);
		$result['Category']['Page'] = ceil($this->model['Category']->countRecordInDatabase('categories') / self::RECORD_PER_PAGE);

		//Загружаем view
		$this->view->render("Home", 'home/', ['products', 'categories'], $result);
	}


}
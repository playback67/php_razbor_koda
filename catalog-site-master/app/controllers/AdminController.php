<?php
//$this - это ССЫЛКА на ТЕКУЩИЙ объект и она нужна, чтобы обратиться к переменной в КОНТЕКСТЕ класса. 
// self ТОЛЬКО для статических функций, свойств. Но также можно вызвать нестатический метод, КАК СТАТИЧЕСКИЙ через self.

namespace app\controllers; // создаем общее пространство имен для папки "контроллеры"


use app\core\Controller; // подключаем класс "Контроллер"

class AdminController extends Controller // создаем класс, который является дочерним от класса "Контроллер" (наследует все общедоступные и защищённые методы, свойства и константы родительского класса)
{
	private $page = 0; // задаем переменной "страница" значение 0
	private const RECORD_PER_PAGE = 100; // фиксируем количество записей=100 для каждой страницы?

	public function __construct($route) // объявляем метод-конструктор
	{
		parent::__construct($route);
		$this->view->layout = 'admin'; // связываемся с html-файлом "админ"
		if (isset($this->route[0]['page'])) { // определяем, отлично ли от null значение переменной
			$this->page = $this->route[0]['page']; // тут не очень понимаю, приравниваем рут и пейдж?
		}

	}

	function indexAction()
	{
		$this->view->render('Admin', 'admin/'); // рендерим админку?
	}

	public function getCategoriesAction(){ // реализуем функцию выбора категории

		$this->loadModel('Category'); // загружаем модель "Категория"??? тут не понял

		$startCategory = $this->page * self::RECORD_PER_PAGE; // выбрать все записи со страницы? не очень понял (для категорий)

		$result['Categories']['Page'] = ceil($this->model['Category']->countRecordInDatabase('categories') / self::RECORD_PER_PAGE); //считаем записи в БД?. Ceil округляет дробь в большую сторону. Связь категория-страница
		$result['Categories']['Data'] = $this->model['Category']->getCategories($startCategory, self::RECORD_PER_PAGE); // делаем связь категория-дата?


		$this->view->render('AdminCategories', 'admin/categories/', [], $result); // рендерим категории
	}

	public function getProductsAction(){ // реализуем функцию выбора товара
		$this->loadModel('Product'); // грузим товары
		$this->loadModel('Category'); // грузим категории

		$startProduct = $this->page * self::RECORD_PER_PAGE; // выбрать все записи со страницы? не очень понял (для продуктов)

		$result['Products']['Page'] = ceil($this->model['Product']->countRecordInDatabase('goods') / self::RECORD_PER_PAGE); //считаем записи в БД?. Ceil округляет дробь в большую сторону. Связь товар-страница
		$result['Products']['Data'] = $this->model['Product']->getProducts($startProduct, self::RECORD_PER_PAGE); // делаем связь товары-дата?

		$result['Categories'] = $this->model['Category']->getAllCategories();

		$this->view->render('AdminProducts', 'admin/products/', [], $result); // рендерим товары
	}

	public function deleteCategoryAction(){ // реализуем функцию удаления категории
		if (isset($_POST['id'])) { // если id не 0
			$id = $_POST['id']; // то фиксируем id
			$this->loadModel('Category'); // грузим модель категории
			$this->model['Category']->deleteCategoryById($_POST['id']); // удаляем категорию, основываясь на id
		}
		$this->view->redirect('/admin/category/page/0'); // редирект на главную страницу
	}

	public function offCategoryAction(){ // реализуем функцию отключения категории
		if (isset($_POST['id'])) { // если id не 0
			$id = $_POST['id']; // то фиксируем id
			$this->loadModel('Category'); // грузим модель категории
			$this->model['Category']->offCategoryById($_POST['id']); // отключаем категорию через её id
		}
		$this->view->redirect('/admin/category/page/0'); // редирект на главную страницу
	}

	public function onCategoryAction(){ // реализуем функцию подключения категории
		if (isset($_POST['id'])) { // если id не 0
			$id = $_POST['id']; // то фиксируем id
			$this->loadModel('Category'); // грузим модель категории
			$this->model['Category']->onCategoryById($_POST['id']); // подключаем категорию через её id
		}
		$this->view->redirect('/admin/category/page/0'); // редирект на главную страницу
	}

	public function deleteProductAction(){ // реализуем функцию удаления товара
		if (isset($_POST['id'])) { // если id не 0
			$id = $_POST['id']; // то фиксируем id
			$this->loadModel('Product'); // грузим модель товара
			$this->model['Product']->deleteProductById($id); // реализуем удаление через id
		}
		$this->view->redirect('/admin/product/page/0'); // редирект на главную страницу
	}
}
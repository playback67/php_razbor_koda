<?php
//$this - это ССЫЛКА на ТЕКУЩИЙ объект и она нужна, чтобы обратиться к переменной в КОНТЕКСТЕ класса. 
// self ТОЛЬКО для статических функций, свойств. Но также можно вызвать нестатический метод, КАК СТАТИЧЕСКИЙ через self.
namespace app\controllers; // создаем общее пространство имен для папки "контроллеры"


use app\core\Controller; // подключаем класс "Контроллер"

class AdminProductController extends Controller // создаем класс, который является дочерним от класса "Контроллер" (наследует все общедоступные и защищённые методы, свойства и константы родительского класса)
{
	private $productId;

	public function __construct($route) // объявляем метод-конструктор
	{
		parent::__construct($route);
		$this->view->layout = 'admin'; // связываемся с html-файлом "админ"
		if (isset($route[0]['productId'])) { // определяем, отлично ли от null значение id товара
			$this->productId = $route[0]['productId']; // тут не очень понимаю, приравниваем рут и пейдж?
		}
	}

	public function indexAction()
	{
		$this->loadModel('Product'); // грузим товары и категории
		$this->loadModel('Category');

		if (!empty($_POST)) { // если пусто- обновляем
			$this->updateAction();
		}

		$result['Product'] = $this->model['Product']->getProductById($this->productId)[0]; // фиксируем товар по ID
		$result['Categories'] = $this->model['Category']->getAllCategories(); // фиксируем категории
		$result['ProductCategory'] = $this->model['Product']->getCategoriesProductById($this->productId); // сопоставляем товар, категорию и ID

		foreach ($result['ProductCategory'] as $item) { // реализуем поиск товара через категорию???
			$productCategories[$item['categoryName']] = $item;
		}
		if (isset($productCategories)) {
			$result['ProductCategory'] = $productCategories;
		}

		$this->view->render($result['Product']['productName'], 'admin/product/', [], $result); // рендерим товар
	}

	private function updateAction() // как я понимаю, тут просто идет обновление странички
	{
		$id = isset($_POST['productId']) ? $_POST['productId'] : ""; // если id пустой
		$name = $_POST['productName']; // имя товара
		$sh = $_POST['descSh']; // вот это вообще не врубаюсь
		$fl = $_POST['descFull']; // вот это вообще не врубаюсь
		if (isset($_POST['isStock'])) { // вот это вообще не врубаюсь
			$iS = $_POST['isStock']=='Yes'? 1 : 0; // вот это вообще не врубаюсь
		}
		$categories = $_POST['categories'];

		if (empty($id)) { // если id пустой 
			$this->addAction($name, $sh, $fl, $categories); // добавляем
		} else {
			$this->model['Product']->update($id, $name, $sh, $fl, $iS, $categories); // если нет - обновляем
		}

		$this->view->redirect('/admin/product/page/0'); // редирект на главную страницу

	}

	private function addAction($name, $sh, $fl, $categories)
	{
		$this->model['Product']->add($name, $sh, $fl, $categories);
	}

}
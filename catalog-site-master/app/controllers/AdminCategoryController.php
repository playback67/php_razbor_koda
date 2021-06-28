<?php
//$this - это ССЫЛКА на ТЕКУЩИЙ объект и она нужна, чтобы обратиться к переменной в КОНТЕКСТЕ класса.
// self ТОЛЬКО для статических функций, свойств. Но также можно вызвать нестатический метод, КАК СТАТИЧЕСКИЙ через self.
namespace app\controllers; // создаем общее пространство имен для папки "контроллеры"


use app\core\Controller; // подключаем класс "Контроллер"
use app\models\Category;  // Подключаем класс "Категория"

class AdminCategoryController extends Controller // создаем класс, который является дочерним от класса "Контроллер" (наследует все общедоступные и защищённые методы, свойства и константы родительского класса)
{
	private $categoryId;

	public function __construct($route) // объявляем метод-конструктор
	{
		parent::__construct($route); // внутри дочернего класса AdminCategoryController получаем доступ к родительскому классу 
		$this->view->layout = 'admin'; // связываемся с html-файлом "админ"
		if (isset($route[0]['categoryId'])) { // определяем, отлично ли от null значение переменной route
			$this->categoryId = $route[0]['categoryId'];
		}

	}

	public function indexAction()
	{
		$this->loadModel('Category'); // загружаем модель "Категория"??? тут не понял
		if (!empty($_POST)) { // проверяем, пустая ли переменная
			$this->updateAction(); // обновляем действие?
		}
		$result['Category'] = $this->model['Category']->getCategoryById($this->categoryId); // разбиваем категории по айдишникам?

		$this->view->render($result['Category']['categoryName'], 'admin/category/', [], $result); // рендерим
	}

	private function updateAction()
	{
		$id = isset($_POST['categoryId']) ? $_POST['categoryId'] : ""; // определяем, отлично ли от null значение переменной categoryId

		$name = $_POST['categoryName'];
		$sh = $_POST['descSh'];
		$fl = $_POST['descFull'];

		if (empty($id)) { //если id пустой, то добавляем действие с вводом данных?
			$this->addAction($name, $sh, $fl);
		} else {
			$this->model['Category']->update($id, $name, $sh, $fl); // если нет, то обновляем данные
		}

		$this->view->redirect('/admin/category/page/0'); // делаем редирект на главную страницу
	}

	private function addAction($name, $sh, $fl)
	{
		$this->model['Category']->add($name, $sh, $fl);
	}


}
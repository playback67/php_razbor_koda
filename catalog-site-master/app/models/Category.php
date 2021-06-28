<?php


namespace app\models; // объявляем пространство имен моделей


use app\core\Model; // подключаем класс "Модель"

class Category extends Model // создаем класс, который является дочерним от класса "Модель" (наследует все общедоступные и защищённые методы, свойства и константы родительского класса)
{
	public function getCategories($start, $take) // осуществляем выбор нужных категорий
	{
		//SELECT * FROM categories LIMIT start, take
		return $this->db->row('SELECT id as `categoryId`,
							   category_name as `categoryName`, 
							   description_short as `categoryDescSh`, 
							   description_full as `categoryDescFl`, 
							   is_activity as `isActivity` 
                                FROM categories 
                                LIMIT ' . $start . ', ' . $take); // тут как я понимаю, мы описываем атрибуты категорий в ряд, то есть id, название и тд
	}
	public function getAllCategories() // такую же функцию пишем для выбора всех категорий
	{
		return $this->db->row('SELECT id as `categoryId`, 
							   category_name as `categoryName`, 
							   description_short as `categoryDescSh`, 
							   description_full as `categoryDescFl`, 
							   is_activity as `isActivity` 
                                FROM categories');
	}

	public function getCategoryByName($categoryName) // сортировка категорий по имени
	{
		return $this->db->row('SELECT id as `categoryId`, 
							   category_name as `categoryName`, 
							   description_short as `categoryDescSh`, 
							   description_full as `categoryDescFl`, 
							   is_activity as `isActivity` 
								FROM categories
								WHERE category_name=:categoryName',
							  [
								  'categoryName' => $categoryName
							  ]);
	}

	public function getCategoryById($id) // сортировка категорий по id
	{
		return $this->db->row('SELECT id as `categoryId`, 
							   category_name as `categoryName`, 
							   description_short as `categoryDescSh`, 
							   description_full as `categoryDescFl`, 
							   is_activity as `isActivity` 
								FROM categories
								WHERE id=:id',
							  [
								  'id' => $id
							  ])[0];
	}

	public function offCategoryById($id) // выключаем поиск по id
	{
		$this->db->row('UPDATE categories SET is_activity = 0 WHERE id = :id',
					   [
						   'id' => $id
					   ]);
	}

	public function onCategoryById($id) // включаем поиск по id
	{
		$this->db->row('UPDATE categories SET is_activity = 1 WHERE id = :id',
					   [
						   'id' => $id
					   ]);
	}

	public function deleteCategoryById($id) // удаляем категорию id
	{

		$this->db->row('DELETE FROM category_product WHERE category_id=:id',
					   [
						   'id' => $id
					   ]);
		$this->db->row('DELETE FROM categories WHERE id=:id',
					   [
						   'id' => $id
					   ]);
	}

	public function update($id, $name, $sh, $fl) // обновление данных по категориям
	{
		$this->db->row("UPDATE categories SET category_name = :name, description_short = :sh, description_full = :fl
						WHERE id = :id",
					   [
						   'id' => $id,
						   'name' => $name,
						   'sh' => $sh,
						   'fl' => $fl
					   ]);
	}

	public function add($name, $sh, $fl) // добавляем категорию
	{
		$this->db->row("INSERT INTO categories(category_name, description_short, description_full, is_activity) 
						VALUES ('$name', '$sh', '$fl', 1)");
	}
}
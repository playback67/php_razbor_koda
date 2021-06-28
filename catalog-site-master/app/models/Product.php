<?php


namespace app\models; // объявляем пространство имен моделей


use app\core\Model; // подключаем класс "Модель"

class Product extends Model // создаем класс, который является дочерним от класса "Модель" (наследует все общедоступные и защищённые методы, свойства и константы родительского класса)
{
	public function getProducts($start, $take) // осуществляем выбор нужных товаров
	{
	{
		return $this->db->row('SELECT id as `productId`, 
							   product_name  as `productName`, 
							   description_short as `productDescSh`, 
							   description_full as `productDescFl`,
							   activity_count as `activityCount`,
								is_stock as `isStock`, 
							   is_ordering_out_of_stock as `isOrdering`
								FROM goods
								LIMIT ' . $start . ', ' . $take); // тут как я понимаю, мы описываем атрибуты товаров в ряд, то есть id, название и тд
	}

	public function getProductById($id) // сортировка товаров по id
	{
		return $this->db->row("SELECT g.id as `productId`, 
							   product_name  as `productName`, 
							   g.description_short as `productDescSh`, 
							   g.description_full as `productDescFl`,
							   activity_count as `activityCount`,
								is_stock as `isStock`, 
							   is_ordering_out_of_stock as `isOrdering`
								FROM goods as g 
								WHERE g.id=:id
								",
							  [
								  'id' => $id,
							  ]);
	}


	public function getCategoriesProductById($productId) // сопоставление товара-категории по id
	{
		return $this->db->row("SELECT c.id as `categoryId`, 
       							c.category_name as `categoryName`
								FROM categories as c
								left join category_product cp 
								    on c.id = cp.category_id
								WHERE cp.product_id = :productId
								ORDER BY c.id",
							  [
								  'productId' => $productId
							  ]);
	}


	public function getGoodsByCategory($category, $start, $take) // про гудс так и не понял. Это мб список желаемого?
	{
		return $this->db->row("SELECT g.id as `productId`, 
							   g.product_name as `productName`, 
							   c.category_name as `categoryName`, 
							   g.description_short as `productDescSh`, 
							   g.description_full as `productDescFl`, 
							   g.is_stock as `isStock`, 
							   g.is_ordering_out_of_stock as `isOrdering`, 
							   g.activity_count as `activityCount`
                                FROM goods as g
                                LEFT JOIN category_product as cp
                                    on g.id = cp.product_id
                                LEFT JOIN categories as c
                                    on cp.category_id = c.id
                                WHERE c.category_name = :category
                                LIMIT " . $start . ', ' . $take,
							  [
								  'category' => $category,
							  ]);
	}

	public function getCountGoodsByCategory($category)
	{
		return intval($this->db->row("SELECT COUNT(*) FROM goods as g 
								left join category_product cp 
								    on g.id = cp.product_id
								left join categories c 
								    on c.id = cp.category_id
								WHERE c.category_name = :category",
									 [
										 'category' => $category,
									 ])[0]['COUNT(*)']);
	}

	public function deleteProductById($id) // удаляем товар по id
	{
		$this->db->row("DELETE FROM category_product WHERE product_id=:id",
					   [
						   'id' => $id
					   ]);

		$this->db->row("DELETE FROM goods WHERE id=:id",
					   [
						   'id' => $id
					   ]);
	}

	public function add($name, $sh, $fl, $categories) // добавляем товар
	{
		$this->db->row("INSERT INTO goods (product_name, description_short, description_full, activity_count, is_stock, is_ordering_out_of_stock) 
						VALUES ('$name', '$sh', '$fl', 0, 1, 1)");

		$id = $this->db->lastInsertId(); // Возвращает ID последней вставленной строки или последовательное значение

		foreach ($categories as $category) {
			$this->db->row("INSERT INTO category_product (category_id, product_id) 
							VALUES ('$category', '$id')"); // размещаем товары в категории
		}
	}

	public function update($id, $name, $sh, $fl, $isStock, $categories)  // обновляем данные
	{
		$this->db->row("UPDATE goods SET product_name = :name, description_short = :sh, description_full = :fl, is_stock=:isStock
						WHERE id = :id",
					   [
						   'id' => $id,
						   'name' => $name,
						   'sh' => $sh,
						   'fl' => $fl,
						   'isStock' => $isStock
					   ]);
		$this->db->row("DELETE FROM category_product WHERE product_id=:id",
					   [
						   'id' => $id
					   ]);

		foreach ($categories as $category) {
			$this->db->row("INSERT INTO category_product (category_id, product_id) 
							VALUES ('$category', '$id')");
		}
	}
}
<?php


namespace app\config; // задаем пространство имен (для удобства в названии написана папка), чтобы в будущем объявлять классы конкретно под данное пространство

use PDO; // через use подключаем класс PDO, отвечающий за подключение сервера к базе данных

class Database // создаем класс с базой данных
{
	protected $db; // настраиванем область видимости через модификатор, тем самым ограничив доступ только для самого класса, его наследникам и родителям

	public function __construct() // объявляем метод-конструктор, в котором будут расположены аргументы и параметры, которые будут заполняться при создании
	{ // тем самым организовываем работу с логином, паролем и тд
		$config = require 'app/config/db.php'; // подключаем файл с логинами и паролями. Отличие от include в остановке скрипта в случае ошибки
		$this->db = new PDO('mysql:host = ' . $config['host'] . ';dbname=' . $config['dbname'],
							$config['login'],
							$config['password']);
	} // проверка совместимости логинов и паролей

	protected function query($sql, $params = []) // объявляем функцию квери
	{
		$stmt = $this->db->prepare($sql); // подготавливаем запрос к выполнению
		if (!empty($params)) { // проверяем, пустая ли переменная
			foreach ($params as $key => $val) {
				$stmt->bindValue(':' . $key, $val); // связываем параметр со значением ключа?
			}
		}
		$stmt->execute(); // запускаем подготовленный запрос на выполнение
		return $stmt;
	} // как я понял, это процесс подключения к БД, так как идет речь о квери, но вообще хз

	public function row($sql, $params = []) // делаем ряды?
	{
		$result = $this->query($sql, $params);
		return $result->fetchAll(PDO::FETCH_ASSOC); //возвращаем массив, содержащий все строки результирующего набора (заполняем только текстовыми индексами)
	}

	public function column($sql, $params = []) // делаем колонки?
	{
		$result = $this->query($sql, $params);
		return $result->fetchColumn(); // возвращаем данные одного столбца
	}

	public function count($sql)
	{
		return intval($this->db->query($sql)->fetch()[0]); //возвращает целые значения переменной. Типа заполняем БД?
	}

	public function lastInsertId()
	{
		return $this->db->lastInsertId(); // функция возвращает последний Айдишник. Зачем?
	}
}
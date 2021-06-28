<?php


namespace app\core; // объявляем пространство имен ядра


abstract class Model // объявляем абстрактный класс модель
{
    public $db; // задаем переменную БД

    public function __construct() // объявляем метод-конструктор
    {
        $this->db = Registry::get('db'); // получаем элемент из реестра БД?
    }


	public function countRecordInDatabase($table) // функция считает записи в БД
	{
		return $this->db->count("SELECT COUNT(*) FROM $table"); // выбирает расчет всего из таблиц???
	}
}
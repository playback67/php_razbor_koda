<?php


namespace app\core; // объявляем пространство имен ядра


class Registry // объявляем класс Registry
{
    private static $_storage = array(); // делаем массив хранилища

    public static function set($key, $value)
    {
        return self::$_storage[$key] = $value; // эту строчку хочу уточнить, не понял про позднее статическое связывание
    }

    public static function get($key, $default = null) 
    {
        return (isset(self::$_storage[$key])) ? self::$_storage[$key] : $default; // эту строчку хочу уточнить, не понял про позднее статическое связывание
    }

    public static function remove($key)
    {
        unset(self::$_storage[$key]); // эту строчку хочу уточнить, не понял про позднее статическое связывание
        return true;
    }

    public static function clean()
    {
        self::$_storage = array(); // эту строчку хочу уточнить, не понял про позднее статическое связывание
        return true;
    }
}
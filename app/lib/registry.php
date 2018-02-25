<?php
namespace PHPMVC\Lib;

/**
 * Class Registry
 * @package PHPMVC\Lib
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class Registry
{

    /**
     * @var Registry singleton instance
     */
    private static $_instance;

    /**
     * Registry constructor.
     */
    private function __construct() {}

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * @return Registry a singleton instance of the class
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param $key
     * @param $object
     */
    public function __set($key, $object)
    {
        $this->$key = $object;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->$key;
    }
}
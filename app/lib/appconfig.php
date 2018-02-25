<?php
namespace PHPMVC\LIB;

/**
 * Class AppConfig Application Configuration Setup
 * @package PHPMVC\LIB
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class AppConfig
{
    /**
     * @var self singleton instance of the class
     */
    private static $_instance;

    /**
     * AppConfig constructor.
     */
    private function __construct() { }

    /**
     * Prevent cloning
     */
    private function __clone() { }

    /**
     * @return AppConfig return a singleton instance
     */
    public static function getInstance() : self
    {
        return self::$_instance === null ? new self() : self::$_instance;
    }

    /**
     * Loads the configuration files from the app/config folder and bootstrap
     * the application
     */
    public function loadAppConfiguration()
    {
        if(!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        $configurationFilesPath = realpath(dirname(__FILE__)) . DS . '..' . DS . 'config' . DS;

        if(file_exists($configurationFilesPath)) {
            if ($handle = opendir($configurationFilesPath)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != '.' && $entry != '..') {
                        require_once $configurationFilesPath . $entry;
                    }
                }
                closedir($handle);
            }
        }
    }
}
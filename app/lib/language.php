<?php
namespace PHPMVC\LIB;

/**
 * Class Language
 * @package PHPMVC\LIB
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class Language 
{
    /**
     * @var array The array containing al loaded language files and its values
     */
    private $dictionary = [];

    /**
     * @param $path the path to the folder in which the language file needed to load
     * @example index.default (index: the folder name, default: the language file default.lang.php)
     */
    public function load($path)
    {
        $defaultLanguage = APP_DEFAULT_LANGUAGE;
        if(isset($_SESSION['lang'])) {
            $defaultLanguage = $_SESSION['lang'];
        }
        $languageFileToLoad = LANGUAGES_PATH . $defaultLanguage . DS . str_replace('.', DS , $path) . '.lang.php';
        if(file_exists($languageFileToLoad)) {
            require $languageFileToLoad;
            if(@is_array($_) && !empty($_)) {
                foreach ($_ as $key => $value) {
                    $this->dictionary[$key] = $value;
                }
            }
        } else {
            trigger_error('Sorry the language file ' . $path . ' doens\'t exists', E_USER_WARNING);
        }
    }

    /**
     * @param $key
     * @return the value of the given language key in the dictionary
     */
    public function get($key)
    {
        if(array_key_exists($key, $this->dictionary)) {
            return $this->dictionary[$key];
        }
    }

    /**
     * @param $key
     * @param $data
     * @return the value of the given key supplied with a data array values
     */
    public function feedKey ($key, $data)
    {
        if(array_key_exists($key, $this->dictionary)) {
            array_unshift($data, $this->dictionary[$key]);
            return call_user_func_array('sprintf', $data);
        }
    }

    /**
     * @param $key
     * @param $data
     * Format the value and supply it with the proper data
     */
    public function swapKey ($key, $data)
    {
        if(array_key_exists($key, $this->dictionary)) {
            array_unshift($data, $this->dictionary[$key]);
            $this->dictionary[$key] = call_user_func_array('sprintf', $data);
        }
    }

    /**
     * @return array the dictionary property value
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }
}
<?php
declare(strict_types=1);
namespace PHPMVC\Classes\Parsers;

/**
 * Class SimpleParserFactory
 * @package PHPMVC\Classes\Parsers
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class SimpleParserFactory
{
    /**
     * @var self the singleton instance
     */
    private static $_instance;

    /**
     * JSONParser Type should return when using this constant
     */
    const JSON_PARSER   = 'JSONParser';

    /**
     * XMLPARSER Type should return when using this constant
     */
    const XML_PARSER    = 'XMLParser';

    /**
     * SimpleParserFactory constructor.
     */
    private function __construct() {}

    /**
     * Preventing cloning to ensure singleton pattern is applied
     */
    private function __clone() {}

    /**
     * @param string $type
     * @return IParser or Exception
     * @throws \Exception
     */
    public static function getInstance($type = self::JSON_PARSER) : IParser
    {
        if(null === self::$_instance) {
            $class = '\\PHPMVC\\Classes\\Parsers\\' . $type;
            if(class_exists($class)) {
                self::$_instance = new $class();
            } else {
                throw new \Exception('Sorry, the parser type is invalid');
            }
        }
        return self::$_instance;
    }
}
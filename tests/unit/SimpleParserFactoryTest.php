<?php
/**
 * Testing SimpleParserFactory Class
 */
use PHPMVC\LIB\AppConfig;

class SimpleParserFactoryTest extends PHPUnit\Framework\TestCase
{
    private $_appConfig;

    public function setUp()
    {
        $this->_appConfig = AppConfig::getInstance();
        $this->_appConfig->loadAppConfiguration();
    }

    public function test_getInstance_method_returns_IParser_instance ()
    {
        $this->assertTrue(\PHPMVC\Classes\Parsers\SimpleParserFactory::getInstance() instanceof \PHPMVC\Classes\Parsers\IParser);
    }
}
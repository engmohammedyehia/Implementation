<?php

use PHPMVC\LIB\AppConfig;

class XMLParserTest extends PHPUnit\Framework\TestCase
{
    private $_appConfig;
    private $_instance;

    public function setUp()
    {
        $this->_appConfig = AppConfig::getInstance();
        $this->_appConfig->loadAppConfiguration();

        $this->_instance = new \PHPMVC\Classes\Parsers\XMLParser();
        $this->_instance->setData([
            [
                'The Gibson',
                '63847 Lowe Knoll, East Maxine, WA 97030-4876',
                '5',
                'Dr. Sinda Wyman',
                '1-270-665-9933x1626',
                'http://www.paucek.com/search.htm'
            ],
            [
                'Martini Cattaneo',
                'Stretto Bernardi 004, Quarto Mietta nell\'emilia, 07958 Torino (OG)',
                '5',
                'Rosalino Marchetti',
                '+39 627 68225719',
                'http://www.farina.org/blog/categories/tags/about.html'
            ],
            [
                'Martini Cattaneo ®',
                'Stretto Bernardi 004, Quarto Mietta nell\'emilia, 07958 Torino (OG)',
                '5',
                'Rosalino Marchetti',
                '+39 627 68225719',
                'http://www.farina.org/blog/categories/tags/about.html'
            ]
        ]);
        $this->_instance->setRequiredFields([0,1,2,3,4,5]);
    }

    public function test_prepare_method_returns_XMLParser_instance ()
    {
        $this->assertInstanceOf('\PHPMVC\Classes\Parsers\XMLParser', $this->_instance->prepare());
    }

    public function test_parse_method_returns_true ()
    {
        $this->assertTrue($this->_instance->prepare()->parse());
    }

    public function test_that_output_is_xml ()
    {
        $this->_instance->prepare()->parse();
        $this->assertXmlStringEqualsXmlString(
            '<?xml version="1.0" encoding="UTF-8"?>
<hotels><hotel><name>The Gibson</name><address>63847 Lowe Knoll, East Maxine, WA 97030-4876</address><stars>5</stars><contact>Dr. Sinda Wyman</contact><phone>1-270-665-9933x1626</phone><url>http://www.paucek.com/search.htm</url></hotel><hotel><name>Martini Cattaneo</name><address>Stretto Bernardi 004, Quarto Mietta nell\'emilia, 07958 Torino (OG)</address><stars>5</stars><contact>Rosalino Marchetti</contact><phone>+39 627 68225719</phone><url>http://www.farina.org/blog/categories/tags/about.html</url></hotel></hotels>',
            $this->_instance->getData()
        );
    }

    public function test_that_file_extension_is_json()
    {
        $this->assertEquals('xml', $this->_instance->getFileExtension());
    }

}
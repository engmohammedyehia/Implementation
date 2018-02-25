<?php

use PHPMVC\LIB\AppConfig;

class JSONParserTest extends PHPUnit\Framework\TestCase
{
    private $_appConfig;
    private $_instance;

    public function setUp()
    {
        $this->_appConfig = AppConfig::getInstance();
        $this->_appConfig->loadAppConfiguration();

        $this->_instance = new \PHPMVC\Classes\Parsers\JSONParser();
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

    public function test_prepare_method_returns_JSONParser_instance ()
    {
        $this->assertInstanceOf('\PHPMVC\Classes\Parsers\JSONParser', $this->_instance->prepare());
    }

    public function test_parse_method_returns_true ()
    {
        $this->assertTrue($this->_instance->prepare()->parse());
    }

    public function test_that_output_is_json ()
    {
        $this->_instance->prepare()->parse();
        $this->assertJson($this->_instance->getData());
    }

    public function test_that_file_extension_is_json()
    {
        $this->assertEquals('json', $this->_instance->getFileExtension());
    }

    public function test_data_filter ()
    {
        try {
            $filter = new \PHPMVC\Classes\Mainpulators\DataFilter($this->_instance->getData(), [0,3]);
            $filter->filterData();
            $filter->getFilters();
            $this->_instance->setData($filter->getFilteredData());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $this->_instance->setRequiredFields([0,3]);
        $this->_instance->prepare()->parse();
        $this->assertJson($this->_instance->getData());
        $this->assertEquals('{
    "data": [
        {
            "name": "The Gibson",
            "contact": "Dr. Sinda Wyman"
        },
        {
            "name": "Martini Cattaneo",
            "contact": "Rosalino Marchetti"
        }
    ]
}', $this->_instance->getData());
    }

    public function test_data_sorter ()
    {
        try {
            $filter = new \PHPMVC\Classes\Mainpulators\DataFilter($this->_instance->getData(), [0,3]);
            $filter->filterData();
            $filter->getFilters();
            $this->_instance->setData($filter->getFilteredData());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        try {
            $sorter = new \PHPMVC\Classes\Mainpulators\DataSorter($this->_instance->getData(), ["0" => 1]);
            $sorter->setFilter($filter);
            $sorter->sortData();
            $this->_instance->setData($sorter->getSortedData());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $this->_instance->setRequiredFields([0,3]);
        $this->_instance->prepare()->parse();
        $this->assertJson($this->_instance->getData());
        $this->assertEquals('{
    "data": [
        {
            "name": "Martini Cattaneo",
            "contact": "Rosalino Marchetti"
        },
        {
            "name": "The Gibson",
            "contact": "Dr. Sinda Wyman"
        }
    ]
}', $this->_instance->getData());
    }

    public function test_data_filter_throws_exception ()
    {
        try {
            $filter = new \PHPMVC\Classes\Mainpulators\DataFilter($this->_instance->getData(), [8,9]);
        } catch (\Exception $e) {
            $this->assertEquals("Sorry, you are using invalid filters", $e->getMessage());
        }
    }

    public function test_data_sorter_throws_exception ()
    {
        try {
            $sorter = new \PHPMVC\Classes\Mainpulators\DataSorter($this->_instance->getData(), [8,9]);
        } catch (\Exception $e) {
            $this->assertEquals("Sorry, you are using invalid sorters", $e->getMessage());
        }
    }
}
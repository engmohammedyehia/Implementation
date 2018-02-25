<?php

use PHPMVC\LIB\AppConfig;

class FilePersistenceTest extends PHPUnit\Framework\TestCase
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

    public function test_saving_file ()
    {
        try {
            $this->_instance->prepare()->parse();
            $filePersistence = new \PHPMVC\Classes\FilePersistence($this->_instance);
            if($filePersistence->save() === true) {
                $path = DOCUMENTS_UPLOAD_STORAGE . DS . $filePersistence->getFileName();
                $this->assertFileExists($path);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
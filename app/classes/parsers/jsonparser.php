<?php
declare(strict_types=1);
namespace PHPMVC\Classes\Parsers;

/**
 * Class JSONParser
 * @package PHPMVC\Classes\Parsers
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class JSONParser extends AbstractParser implements IParser
{
    /**
     * @var string The file extension used to save the file to disk
     */
    protected $_fileExtension = 'json';

    /**
     * Parse the filtered and sorted data to the proper JSON format
     * @return bool true if parsing is successfully completed, false otherwise
     */
    public function parse() : bool
    {
        $encodedData = json_encode(['data' => $this->getData()], JSON_PRETTY_PRINT);
        if(false !== $encodedData) {
            $this->_data = $encodedData;
            return true;
        }
        return false;
    }
}
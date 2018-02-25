<?php
declare(strict_types=1);
namespace PHPMVC\Classes\Parsers;

/**
 * Class XMLParser
 * @package PHPMVC\Classes\Parsers
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class XMLParser extends AbstractParser implements IParser
{
    /**
     * @var string The file extension used to save the file to disk
     */
    protected $_fileExtension = 'xml';

    /**
     * Parse the filtered and sorted data to the proper JSON format
     * @return bool true if parsing is successfully completed, false otherwise
     */
    public function parse() : bool
    {
        $data = $this->getData();

        $XMLData = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><hotels></hotels>');

        foreach ($data as $hotel) {
            $hotelChild = $XMLData->addChild('hotel');

            for ( $i = 0, $ii = count($this->_requiredField); $i < $ii; $i++ ) {
                $hotelChild->addChild($this->_requiredField[$i], $hotel->{$this->_requiredField[$i]});
            }
        }

        $finalData = $XMLData->asXML();

        if($finalData !== false) {
            $this->_data = $finalData;
            return true;
        }

        return false;
    }
}
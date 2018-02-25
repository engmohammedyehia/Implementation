<?php
declare(strict_types=1);
namespace PHPMVC\classes\parsers;

use PHPMVC\LIB\InputFilter;
use PHPMVC\Lib\Validate;

/**
 * Class AbstractParser
 * @package PHPMVC\classes\parsers
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
abstract class AbstractParser
{
    /**
     * @var array the valid fields map
     */
    private $_fieldsMap = ['name', 'address', 'stars', 'contact', 'phone', 'url'];

    /**
     * @var array The indexes of the fields map used as required fields when using filter
     */
    protected $_requiredField = [0,1,2,3,4,5];

    /**
     * @var string|array the data to be parsed
     */
    protected $_data;

    /**
     * @var string the file extension of the called format (JSON, XML)
     */
    protected $_fileExtension;

    use InputFilter;
    use Validate;

    /**
     * @param array $CSVData the data attribute setter
     */
    public function setData(array $CSVData)
    {
        $this->_data = $CSVData;
    }

    /**
     * @param array $fields the requiredFields attribute setter
     * if no filters are sent then we assume that tha required fields remain the same
     */
    public function setRequiredFields (array $fields)
    {
        $this->_requiredField = [];
        foreach ($fields as $field) {
            $this->_requiredField[] = $this->_fieldsMap[$field];
        }
    }

    /**
     * @return array|string the data array attribute
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * The method is used to prepare the data after filtering and sorting
     * then makes it ready to be parsed to the required output format.
     *
     * Make sure to read the comments inside the prepare method
     * @return IParser
     */
    public function prepare () : IParser
    {
        $hotels = $this->getData();
        $finalHotelsList = [];

        for ($i = 0, $ii = count($hotels); $i < $ii; $i++) {

            $hotel = new \stdClass();

            for ($j = 0, $jj = count($this->_requiredField); $j < $jj; $j++) {
                $hotel->{$this->_requiredField[$j]} = $this->filterString($hotels[$i][$j], true);
            }

            // This part ensures the validation data in the task
            /**
             *  -- A hotel name may not contain non-ASCII characters. (Only ASCII within range (0-127)
             *     will be allowed, therefore umlauts in the extended ASCII table will be discarded.
                -- The hotel URL must be valid (please come up with a good definition of "valid").
                -- Hotel ratings are given as a number from 0 to 5 stars (never negative numbers).
             */
            if((isset($hotel->name) && $this->isASCII($hotel->name) === false) ||
                (isset($hotel->stars) && $this->between($hotel->stars, 0, 5) === false) ||
                (isset($hotel->url) && $this->url($hotel->url) === false))
            {
                continue;
            }

            $finalHotelsList[] = $hotel;
        }

        $this->setData($finalHotelsList);

        return $this;
    }

    /**
     * @return string the file extension needed to save the output to a file on the desk
     */
    public function getFileExtension()
    {
        return $this->_fileExtension;
    }
}
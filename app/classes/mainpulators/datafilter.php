<?php
declare(strict_types=1);
namespace PHPMVC\Classes\Mainpulators;

/**
 * Class DataFilter
 * @package PHPMVC\Classes\Mainpulators
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */

class DataFilter
{
    /**
     * @var array the data to be filtered
     */
    private $_data = [];

    /**
     * @var array the required filters to filter the data based on the valid filters
     * @example [0,1] as sent from the Web UI mapped to [name, address]
     */
    private $_filters = [];

    /**
     * @var array the valid data filters only mapped to [name, address, stars, contact, phone, url]
     * this attribute is used to check if non-valid filter has been set
     */
    private $_validFilters = [0,1,2,3,4,5];

    /**
     * DataFilter constructor.
     * @param array $data the data to be filtered
     * @param array $filters the required columns from data
     * @throws \Exception
     */
    public function __construct(array $data, array $filters)
    {
        if(!empty(array_diff($filters, $this->_validFilters))) {
            throw new \Exception('Sorry, you are using invalid filters');
        }
        $this->_data = $data;
        $this->_filters = $filters;
    }

    /**
     * @return array the filtered data array
     */
    public function getFilteredData() : array
    {
        return $this->_data;
    }

    /**
     * @return array the required filters
     */
    public function getFilters() : array
    {
        return $this->_filters;
    }

    /**
     * This method extracts the columns from the main data array
     * and then rebuild the filtered array combining column values again
     * then sets the data attribute to the filtered array
     */
    public function filterData()
    {
        $columns = [];
        foreach ($this->_filters as $filter) {
            $columns[] = array_column($this->_data, $filter);
        }
        $finalDataArray = [];
        for ($i = 0, $ii = count($columns[0]); $i < $ii; $i++) {
            $finalDataArray[$i] = [];
            for ($j = 0, $jj = count($columns); $j < $jj; $j++) {
                $finalDataArray[$i][$j] = $columns[$j][$i];
            }
        }
        $this->_data = $finalDataArray;
    }
}
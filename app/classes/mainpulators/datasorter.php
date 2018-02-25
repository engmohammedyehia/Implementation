<?php
declare(strict_types=1);
namespace PHPMVC\Classes\Mainpulators;

/**
 * Class DataSorter
 * @package PHPMVC\Classes\Mainpulators
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class DataSorter
{
    /**
     * @var array the data to be sorted
     */
    private $_data          = [];

    /**
     * @var array the array of sorters
     * @example [0 => 1, 2 => 2] and this is mapped to [name => SORT_ASC, stars => SORT_DESC]
     */
    private $_sorters       = [];

    /**
     * @var array the valid sorters to compare against to make sure non-valid sorters are passed in
     */
    private $_validSorters  = [0,1,2,3];

    /**
     * @var DataFilter is set only in case a filter has been called on the data array first
     * this is used to make sure proper data return
     */
    private $_filter;

    /**
     * DataSorter constructor.
     * @param array $data data array to be sorted
     * @param array $sorters sorters needed to sort data
     * @throws \Exception
     */
    public function __construct(array $data, array $sorters)
    {
        if(!empty(array_diff($sorters, $this->_validSorters))) {
            throw new \Exception('Sorry, you are using invalid sorters');
        }
        $this->_data = $data;
        $this->_sorters = $sorters;
    }

    /**
     * @return array the sorted data
     */
    public function getSortedData() : array
    {
        return $this->_data;
    }

    /**
     * @param DataFilter $filter inject the dependant $filter object when needed
     */
    public function setFilter(DataFilter $filter)
    {
        $this->_filter = array_flip($filter->getFilters());
    }

    /**
     * Sorts the data using eval to call the array_multisort function regarding passed sorters
     * to ensure non-valid sorters are passed through the web ui I validate sorters in the constructor
     * first throwing an exception if non-valid ones detected
     */
    public function SortData()
    {
        $evalParameters = [];
        foreach ($this->_sorters as $sorterKey => $order) {
            // this little trick is to make sure proper data are sent back when a filter is being used
            // ($this->_filter !== null ? $this->_filter[$sorterKey] : $sorterKey)
            $evalParameters[] = 'array_column($this->_data, ' . ($this->_filter !== null ? $this->_filter[$sorterKey] : $sorterKey) . '), ' . ((int) $order === 1 ? SORT_ASC : SORT_DESC);
        }
        $evaluated = 'array_multisort(' . implode(', ', $evalParameters) . ',  $this->_data);';
        eval($evaluated);
    }
}
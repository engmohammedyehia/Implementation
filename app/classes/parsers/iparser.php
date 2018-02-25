<?php
namespace PHPMVC\Classes\Parsers;

/**
 * Interface IParser
 * @package PHPMVC\Classes\Parsers
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
Interface IParser
{
    /**
     * @return bool true if parsing is successfully completed, false otherwise
     */
    function parse ();
}
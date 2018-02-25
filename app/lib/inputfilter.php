<?php
namespace PHPMVC\LIB;

/**
 * Trait InputFilter
 * @package PHPMVC\LIB
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
trait InputFilter
{
    /**
     * @param $input the value
     * @return the filtered input in an integer format
     */
    public function filterInt($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * @param $input the value
     * @return the filtered input in a float format
     */
    public function filterFloat($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * @param $input string the value
     * @param bool $allowUmlauts true to allow German Umlauts
     * @return string the filtered string
     */
    public function filterString($input, $allowUmlauts = false)
    {
        return $allowUmlauts === false ? htmlentities(strip_tags($input), ENT_QUOTES, 'UTF-8') : strip_tags($input);
    }

    /**
     * @param $email the value
     * @return string the filtered email address in a string format
     */
    public function filterEmail($email)
    {
        return htmlentities(filter_var(trim($email), FILTER_SANITIZE_EMAIL), ENT_QUOTES, 'utf-8');
    }

    /**
     * @param array $arr
     * @return array the filtered array values
     */
    public function filterStringArray(array $arr)
    {
        foreach ($arr as $key => $value) {
            $arr[$key] = htmlentities(filter_var(trim($value), FILTER_SANITIZE_STRING), ENT_QUOTES, 'utf-8');
        }
        return $arr;
    }
}
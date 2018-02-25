<?php
namespace PHPMVC\LIB\Template;

/**
 * Trait TemplateHelper
 * @package PHPMVC\LIB\Template
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
trait TemplateHelper
{
    /**
     * @param $urls
     * @return bool
     * Match a part from the url against a given string
     */
    public function matchUrl($urls) {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if(is_array($urls) && !empty($urls)) {
            foreach ($urls as $url) {
                if((bool) preg_match("/" . preg_quote($url, '/'). "$/i", $path) === true) {
                    return true;
                }
            }
        } else {
            return $path === $urls;
        }
    }

    /**
     * @param $fieldName
     * @param null $object
     * @return string
     * View Helper
     */
    public function labelFloat($fieldName, $object = null)
    {
        return ((isset($_POST[$fieldName]) && !empty($_POST[$fieldName])) || (null !== $object && $object->$fieldName !== null)) ? ' class="floated"' : '';
    }

    /**
     * @param $fieldName
     * @param null $object
     * @return string
     * View Helper
     */
    public function showValue($fieldName, $object = null)
    {
        return isset($_POST[$fieldName]) ? $_POST[$fieldName] : (is_null($object) ? '' : $object->$fieldName);
    }

    /**
     * @param $fieldName
     * @param $value
     * @param null $object
     * @return string
     * View Helper
     */
    public function selectedIf($fieldName, $value, $object = null)
    {
        return ((isset($_POST[$fieldName]) && $_POST[$fieldName] == $value) || (!is_null($object) && $object->$fieldName == $value)) ? 'selected="selected"' : '';
    }

    /**
     * @param $fieldName
     * @param $value
     * @param null $object
     * @return string
     * View Helper
     */
    public function radioCheckedIf($fieldName, $value, $object = null)
    {
        return ((isset($_POST[$fieldName]) && $_POST[$fieldName] == $value) || ($object !== null && $object->$fieldName == $value)) ? 'checked' : '';
    }

    /**
     * @param $fieldName
     * @param $value
     * @param null $object
     * @return string
     * View Helper
     */
    public function boxCheckedIf($fieldName, $value, $object = null)
    {
        return ((isset($_POST[$fieldName]) && is_array($_POST[$fieldName]) && in_array($value, $_POST[$fieldName])) || ($object !== null && is_array($object->$fieldName) && in_array($value, $object->$fieldName))) ? 'checked' : '';
    }
}
<?php
namespace PHPMVC\Lib;

/**
 * Trait Validate
 * @package PHPMVC\Lib
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
trait Validate
{

    /**
     * @var array Validation regular expressions patterns
     */
    private $_regexPatterns = [
        'num'           => '/^[0-9]+(?:\.[0-9]+)?$/',
        'int'           => '/^[0-9]+$/',
        'float'         => '/^[0-9]+\.[0-9]+$/',
        'alpha'         => '/^[a-zA-Z\p{Arabic} ]+$/u',
        'alphanum'      => '/^[a-zA-Z\p{Arabic}0-9\p{N} ]+$/u',
        'vdate'         => '/^[1-2][0-9][0-9][0-9]-(?:(?:0[1-9])|(?:1[0-2]))-(?:(?:0[1-9])|(?:(?:1|2)[0-9])|(?:3[0-1]))$/',
        'email'         => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'url'           => '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        'nonascii'      => '/[^\x00-\x7F]/u'
    ];

    /**
     * @param $value
     * @return bool true if the value is required, false otherwise
     */
    public function req ($value)
    {
        return '' != $value || !empty($value);
    }

    /**
     * @param $value
     * @return bool true if the value is numeric, false otherwise
     */
    public function num($value)
    {
        return (bool) preg_match($this->_regexPatterns['num'], $value);
    }

    /**
     * @param $value
     * @return bool true if the value is integer, false otherwise
     */
    public function int($value)
    {
        return (bool) preg_match($this->_regexPatterns['int'], $value);
    }

    /**
     * @param $value
     * @return bool true if the value is float, false otherwise
     */
    public function float($value)
    {
        return (bool) preg_match($this->_regexPatterns['float'], $value);
    }

    /**
     * @param $value
     * @return bool true if the value is alphabet, false otherwise
     */
    public function alpha($value)
    {
        return (bool) preg_match($this->_regexPatterns['alpha'], $value);
    }

    /**
     * @param $value
     * @return bool true if the value is alphanumeric, false otherwise
     */
    public function alphanum($value)
    {
        return (bool) preg_match($this->_regexPatterns['alphanum'], $value);
    }

    /**
     * @param $value
     * @return bool true if the value lies within the ASCII range (0-127), false otherwise
     */
    public function isASCII ($value) {
        return mb_check_encoding($value, 'ASCII');
    }

    /**
     * @param $value
     * @param $matchAgainst
     * @return bool true if the value is equals to the $matchAgainst, false otherwise
     */
    public function eq($value, $matchAgainst)
    {
        return $value == $matchAgainst;
    }

    /**
     * @param $value
     * @param $otherFieldValue
     * @return bool true if the value is equals to the $otherFieldValue, false otherwise
     */
    public function eq_field($value, $otherFieldValue)
    {
        return $value == $otherFieldValue;
    }

    /**
     * @param $value
     * @param $matchAgainst
     * @return bool true if the value is less than to the $matchAgainst, false otherwise
     */
    public function lt($value, $matchAgainst)
    {
        if(is_string($value)) {
            return mb_strlen($value) < $matchAgainst;
        } elseif (is_numeric($value)) {
            return $value < $matchAgainst;
        }
    }

    /**
     * @param $value
     * @param $matchAgainst
     * @return bool true if the value is greater than to the $matchAgainst, false otherwise
     */
    public function gt($value, $matchAgainst)
    {
        if(is_string($value)) {
            return mb_strlen($value) > $matchAgainst;
        } elseif (is_numeric($value)) {
            return $value > $matchAgainst;
        }
    }

    /**
     * @param $value
     * @param $min
     * @return bool true if the value is greater than or equals $min
     */
    public function min($value, $min)
    {
        if(is_string($value)) {
            return mb_strlen($value) >= $min;
        } elseif (is_numeric($value)) {
            return $value >= $min;
        }
    }

    /**
     * @param $value
     * @param $max
     * @return bool true if the value is less than or equals $max
     */
    public function max($value, $max)
    {
        if(is_string($value)) {
            return mb_strlen($value) <= $max;
        } elseif (is_numeric($value)) {
            return $value <= $max;
        }
    }

    /**
     * @param $value
     * @param $min
     * @param $max
     * @return bool true if the value is between $min and $max or strlen($min) and strlen($max)
     */
    public function between($value, $min, $max)
    {
        if(is_string($value)) {
            return mb_strlen($value) >= $min && mb_strlen($value) <= $max;
        } elseif (is_numeric($value)) {
            return $value >= $min && $value <= $max;
        }
    }

    /**
     * @param $value
     * @param $set
     * @return bool true if the value is within the specified range $set, false otherwise
     */
    public function inset($value, $set)
    {
        $set = str_replace('[', '', $set);
        $set = str_replace(']', '', $set);
        $set = preg_replace('/(\s*)(,)(\s*)/', '$2', $set);
        $set = explode(',',$set);
        return in_array($value, $set);
    }

    /**
     * @param $value
     * @param $language
     * @return bool true if the value is of language $language (Supporting English only for now)
     */
    public function lang($value, $language)
    {
        if($value == '') return true;
        $languages = [
            'en' => '[a-zA-Z-0-9]'
        ];
        return (bool) preg_match('/^' . $languages[$language] . '+$/', $value);
    }

    /**
     * @param $value
     * @param $beforeDP
     * @param $afterDP
     * @return bool true if the float value is of format "$beforeDP.$afterDP"
     * @example 1.23 = 1.23
     */
    public function floatlike($value, $beforeDP, $afterDP)
    {
        if(!$this->float($value))
        {
            return false;
        }
        $pattern = '/^[0-9]{' . $beforeDP . '}\.[0-9]{' . $afterDP . '}$/';
        return (bool) preg_match($pattern, $value);
    }

    /**
     * @param $value
     * @return bool true if the value is a valid date, false otherwise
     */
    public function vdate($value)
    {
        return (bool) preg_match($this->_regexPatterns['vdate'], $value);
    }

    /**
     * @param $value
     * @return bool true if the value is a valid email string, false otherwise
     */
    public function email($value)
    {
        return (bool) preg_match($this->_regexPatterns['email'], $value);
    }

    /**
     * @param $value
     * @return bool true if the url is valid, false otherwise
     */
    public function url($value)
    {
        return (bool) preg_match($this->_regexPatterns['url'], $value);
    }

    /**
     * @param $roles
     * @param $inputType
     * @return bool check against each role by calling the related method
     */
    public function isValid($roles, $inputType)
    {
        $errors = [];
        if(!empty($roles)) {
            foreach ($roles as $fieldName => $validationRoles) {
                $value = $inputType[$fieldName];
                $validationRoles = explode('|', $validationRoles);
                foreach ($validationRoles as $validationRole) {
                    if(array_key_exists($fieldName, $errors))
                        continue;
                    if(preg_match_all('/(min)\((\d+)\)/', $validationRole, $m)) {
                        if($this->min($value, $m[2][0]) === false) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$m[1][0], [$this->language->get('text_label_'.$fieldName), $m[2][0]]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    } elseif (preg_match_all('/(max)\((\d+)\)/', $validationRole, $m)) {
                        if($this->max($value, $m[2][0]) === false) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$m[1][0], [$this->language->get('text_label_'.$fieldName), $m[2][0]]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    } elseif(preg_match_all('/(lt)\((\d+)\)/', $validationRole, $m)) {
                        if($this->lt($value, $m[2][0]) === false) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$m[1][0], [$this->language->get('text_label_'.$fieldName), $m[2][0]]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    } elseif(preg_match_all('/(gt)\((\d+)\)/', $validationRole, $m)) {
                        if($this->gt($value, $m[2][0]) === false) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$m[1][0], [$this->language->get('text_label_'.$fieldName), $m[2][0]]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    } elseif(preg_match_all('/(between)\((\d+),(\d+)\)/', $validationRole, $m)) {
                        if($this->between($value, $m[2][0], $m[3][0]) === false) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$m[1][0], [$this->language->get('text_label_'.$fieldName), $m[2][0], $m[3][0]]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    } elseif(preg_match_all('/(floatlike)\((\d+),(\d+)\)/', $validationRole, $m)) {
                        if($this->floatlike($value, $m[2][0], $m[3][0]) === false) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$m[1][0], [$this->language->get('text_label_'.$fieldName), $m[2][0], $m[3][0]]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    } elseif(preg_match_all('/(eq)\((\w+)\)/', $validationRole, $m)) {
                        if($this->eq($value, $m[2][0]) === false) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$m[1][0], [$this->language->get('text_label_'.$fieldName), $m[2][0]]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    } elseif(preg_match_all('/(eq_field)\((\w+)\)/', $validationRole, $m)) {
                        $otherFieldValue = $inputType[$m[2][0]];
                        if($this->eq_field($value, $otherFieldValue) === false) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$m[1][0], [$this->language->get('text_label_'.$fieldName), $this->language->get('text_label_'.$m[2][0])]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    } elseif(preg_match_all('/(inset)(\[(?:\s*[\'"]?\w+[\'"]?\s*,?\s*)+\])/', $validationRole, $m)) {
                        if($this->inset($value, $m[2][0]) === false) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$m[1][0], [$this->language->get('text_label_'.$fieldName), $m[2][0]]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    } elseif(preg_match_all('/(lang)\((\w+)\)/', $validationRole, $m)) {
                        if($this->lang($value, $m[2][0]) !== true) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$m[1][0], [$this->language->get('text_label_'.$fieldName), $m[2][0]]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    } else {
                        if($this->$validationRole($value) === false) {
                            $this->messenger->add(
                                $this->language->feedKey('text_error_'.$validationRole, [$this->language->get('text_label_'.$fieldName)]),
                                Messenger::APP_MESSAGE_ERROR
                            );
                            $errors[$fieldName] = true;
                        }
                    }
                }
            }
        }
        return empty($errors) ? true : false;
    }

}
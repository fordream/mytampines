<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_ROOT.DS.'components'.DS.'com_hub2'.DS.'models'.DS.'staticdata.php');

/**
 * Site Table
 *
 */
class Hub2DataModelValidatorGeneric extends JObject {

    var $_map = array();

    var $_staticDatamodel;

    public function __construct() {
        $this->_staticDatamodel =  Hub2ModelStaticData::getAnInstance();
    }

    function loadAndSetFieldValidateRules($code) {
        $items = $this->_staticDatamodel->getItems($code, false);
        foreach ($items as $item) {
            $cvalue = $item->cvalue;
            $values = explode('##', $cvalue);
            $fieldname = $values[0];
            $type = $values[1];
            $this->setValidateFieldsMap($fieldname, $type);
        }
    }

    /**
     * @param array $fields - array of field objects where each object has name and type properties
     */
    function setValidateFieldsMap($fieldname, $type) {
        $this->_map[$fieldname] = $type;
        return true;
    }

    /**
     * Validate object fields in data
     * @param array $invalids map to store validation error messages.
     */
    public function validate($tableObject, &$invalids = array()) {
        $count = 0;
        foreach (get_object_vars( $tableObject ) as $k => $v) {
            if (is_array($v) or is_object($v)) {
                continue;
            }
            if (!array_key_exists($k, $this->_map)) {
                continue;
            }
            $type = $this->_map[$k];
            $validators = explode(';', $type);
            foreach ($validators as $validator) {
                $function = $this->parseMethodSignature($validator);
                $method = 'validate' . ucfirst($function[0]);
                if (method_exists($this, $method)) {
                    if (count($function) > 1) {
                        $args = $function[1];
                        $valid = $this->$method($k, $v, $args, $invalids);
                    } else {
                        $valid  = $this->$method($k, $v, $invalids);
                    }
                    if (!$valid) {
                        $count++;
                    }
                } else {
                    JError::raiseError(404, 'Validator for item [' . $validator .
                        '] does not exists in class Hub2DataModelValidatorGeneric');
                    return false;
                }
            }
        }
        return ($count == 0);
    }

    public function validateRequired($name, $value, &$invalids) {
        if (empty($value)) {
            $invalids[$name] = 'Value is required for field "' . $name . '"';
            return false;
        }
        return true;
    }

    public function validateUsername($name, $value, &$invalids) {
        $regx = '[\<|\>|\"|\'|\%|\;|\(|\)|\&]'; // not allowed
        $ret = preg_match($regx, $value);
        if ($ret) {
            $invalids[$name] = 'Value has character(s) not allowed for field"' . $name . '"';
            return false;
        }
        return true;
    }

    public function validateNumeric($name, $value, &$invalids) {
        $regx = '/^(\d|-)?(\d|,)*\.?\d*$/';
        $ret = preg_match($regx, $value);
        if (!$ret) {
            $invalids[$name] = 'Value is not numeric for field "' . $name . '"';
            return false;
        }
        return true;
    }

    public function validateEmail($name, $value, &$invalids) {
        if (empty($value)) {
            return true;
        }
        $regx='/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/';
        $ret = preg_match($regx, $value);
        if (!$ret) {
            $invalids[$name] = 'Value is not an email address for field "' . $name . '"';
            return false;
        }
        return true;
    }

    public function validateUrl($name, $value, &$invalids) {
        if (empty($value)) {
            return true;
        }
        $regx = '/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?';
        $regx .= '(\/|\/([\w#!:.?+=&%@!\-\/]))?/';
        $ret = preg_match($regx, $value);
        if (!$ret) {
            $invalids[$name] = 'Value is not a valid URL for field "' . $name . '"';
            return false;
        }
        return true;
    }

    /**
     * Validate that a string data has number of characters within range specified
     * @param string data to validate
     * @param integer min number of character (exclusive)
     * @param integer max number of character (inclusive)
     * @return boolean true if input data has number of characters within range
     */
    public function validateLength($name, $data, $args, &$invalids) {
        $min = (int)$args[0];
        $max = (int)$args[1];
        $validateString = true;
        if ($min <= 0) {
            $min = 0;
            $validateString = false;
        }
        if ($validateString && !is_string($data)) {
            $invalids[$name] = 'Value is not a string for field "' . $name . '"';
            return false;
        }
        $ret = strlen($data) >= $min && strlen($data) <= $max;
        if (!$ret) {
            $invalids[$name] = 'String size not within range';
        }
        return $ret;
    }

    function parseMethodSignature($sign) {
        $args = array();
        // split name and arguments
        $a = explode('(', trim($sign));
        $args[] = trim($a[0]);
        if (count($a) > 1) { // has argument
            $b = str_replace(')', '', trim($a[1]));
            $c = explode(',', $b);
            $trimc = array();
            foreach ($c as $tc) { // get rid of any whitespaces
                $trimc[] = trim($tc);
            }
            $args[] = $trimc;
        }
        return $args;
    }
}
<?php
/**
 * The bulk of this code is from jXtended libraries form/fields/registry.php
 */

defined('JPATH_BASE') or die;

jximport('jxtended.form.fields.registry');

/**
 * Hub2Registry field type
 * created since Jxtended registry cannot differentiate between "" and 0 as a value for a field
 */
class JXFieldTypeHub2Registry extends JXFieldTypeRegistry {

    /**
     * Field type
     *
     * @access   protected
     * @var      string
     */
    var $_type = 'Hub2Registry';

    function fetchField($name, $value, &$node, $controlName) {
        if (is_string($value)) {
            $params = new JParameter($value);
            $values = $params->toArray();
        } else {
            $values = (array)$value;
        }

        $fields = array();
        if (!empty($node->param)) {
            foreach ($node->param as $param) {
                // choose the value to send
                if (array_key_exists($param->attributes('name'),$values)) {
                    if ($values[$param->attributes('name')] != '') {
                        $val = $values[$param->attributes('name')];
                    } else {
                        $val = $param->attributes('default', null);
                    }
                } else {
                    $val = $param->attributes('default', null);
                }
                // check if no control name
                $fname = $controlName.'['.$name.']';
                if ($controlName == '') {
                    $fname = $name;
                }
                $fields[$param->attributes('name')] = $this->_getField($param,$val,$fname);
            }
        }

        return $fields;
    }
}
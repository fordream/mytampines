<?php
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jximport('jxtended.form.field');

class JXFieldTypeMultiSelect extends JXFieldType {
    /**
     * Field type
     *
     * @access   protected
     * @var      string
     */
    var $_type = 'MultiSelect';

    function _getOptions(&$node) {
        $options = array ();
        foreach ($node->children() as $option) {
            $val    = $option->attributes('value');
            $text   = trim($option->data());
            $options[] = JHTML::_('select.option', $val, JText::_($text));
        }
        return $options;
    }

    function fetchField($name, $value, &$node, $controlName) {
        $id         = str_replace(']', '', str_replace('[', '_', $controlName.'_'.$name));
        $size       = $node->attributes('size')?' size="'.$node->attributes('size').'"':'';
        $class      = ($node->attributes('class') ? 
                        ' class="'.$node->attributes('class').'"' : 'class="inputbox"');
        $disabled   = $node->attributes('disabled');
        $readonly   = $node->attributes('readonly');
        $cname = $controlName.'['.$name.']';
        if ($node->attributes('multiple') == 'multiple') {
            $multiple   = ' multiple="'.$node->attributes('multiple').'"';
            $cname = str_replace(']', '', str_replace('[', '_', $controlName.'_'.$name)).'[]';
        } else {
            $multiple = '';
        }
        if ($disabled == 'true') {
            $disabled   = ' disabled="disabled"';
            $html       = JHTML::_('select.genericlist', $this->_getOptions($node), $cname,
                        $class.$multiple.$size.$disabled, 'value', 'text', $value, $id);
        } else if ($readonly == 'true') {
            $html       = JHTML::_('select.genericlist', $this->_getOptions($node), '',
                        $class.$multiple.$size.' disabled="disabled"', 'value', 'text', $value, $id)
            . '<input type="hidden" name="'.$cname.'" value="'.$value.'" />';
        } else {
            $html   = JHTML::_('select.genericlist', $this->_getOptions($node), $cname, 
                        $class.$multiple.$size, 'value', 'text', $value, $id);
        }
        return $html;
    }
}
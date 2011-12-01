<?php
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jximport('jxtended.form.field');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS.'language.php');

class JXFieldTypeHub2Lang extends JXFieldType {
    /**
     * Field type
     *
     * @access   protected
     * @var      string
     */
    var $_type = 'Hub2Lang';

    function _getOptions(&$node) {
        $lmodel = new Hub2ModelLanguage();
        $results = $lmodel->getLanguages();
        $langoptions = array();
        $langoptions[] = JHTML::_('select.option', '', JText::_('SELECT_LABEL_OPTION'));
        foreach($results as $result) {
            $langoptions[] = JHTML::_('select.option', $result->cvalue, $result->name);
        }
        return $langoptions;
    }

    function fetchField($name, $value, &$node, $controlName) {
        $id         = str_replace(']', '', str_replace('[', '_', $controlName.'_'.$name));
        $size       = $node->attributes('size')?' size="'.$node->attributes('size').'"':'';
        $class      = ($node->attributes('class') ?
                        ' class="'.$node->attributes('class').'"' : 'class="inputbox"');
        $disabled   = $node->attributes('disabled');
        $readonly   = $node->attributes('readonly');
        $cname = $controlName.'['.$name.']';
        if ($disabled == 'true') {
            $disabled   = ' disabled="disabled"';
            $html       = JHTML::_('select.genericlist', $this->_getOptions($node), $cname,
            $class.$size.$disabled, 'value', 'text', $value, $id);
        } else if ($readonly == 'true') {
            $html       = JHTML::_('select.genericlist', $this->_getOptions($node), '',
            $class.$size.' disabled="disabled"', 'value', 'text', $value, $id)
            . '<input type="hidden" name="'.$cname.'" value="'.$value.'" />';
        } else {
            $html   = JHTML::_('select.genericlist', $this->_getOptions($node), $cname,
            $class.$size, 'value', 'text', $value, $id);
        }
        return $html;
    }
}
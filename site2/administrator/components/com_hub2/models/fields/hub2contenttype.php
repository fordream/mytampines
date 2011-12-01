<?php
jimport('joomla.html.html');
jximport('jxtended.form.field');
require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'models'.DS.'contenttypes.php');
require_once(dirname(__FILE__).DS.'multiselect.php');

class JXFieldTypeHub2ContentType extends JXFieldTypeMultiSelect {
    /**
     * Field type
     *
     * @access   protected
     * @var      string
     */
    var $_type = 'Hub2ContentType';

    function _getOptions(&$node) {
        $model = Hub2ModelContentTypes::getContentTypeInstance();
        $results = $model->getContentTypes();
        $options = array();
        foreach($results as $result) {
            $options[] = JHTML::_('select.option', $result->id, $result->name);
        }
        return $options;
    }
}
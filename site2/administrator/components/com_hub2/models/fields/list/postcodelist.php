<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.html.html' );
jximport('jxtended.form.field');
require_once(dirname(__FILE__).DS.'..'.DS.'multiselect.php');

/**
 * Form field type object to return list of regions
 *
 */
class JXFieldTypeList_PostcodeList extends JXFieldTypeMultiSelect {

    function _getOptions( &$node ) {
        $jApp = &JFactory::getApplication();

        $cid    = JRequest::getVar( 'cid', array(0), '', 'array' );
        $id     = JRequest::getVar( 'id', $cid[0], '', 'int' );
        $postcode_id  = $id;
        $view = JRequest::getVar('view','');
        if ($view !== 'postcode') {
            $postcode_id = 0;
        }

        $options = array();
        if ($node->attributes("addnone") !== "false") {
            $options[] = JHTML::_('select.option', '', '-- '.JText::_( 'None' ).' --');
        }
        $model      = Hub2DataModel::getInstance( 'postcode');
        $result	= $model->getPostcodes();
        foreach ($result as $item) {
            if ((int)$item->id !== $postcode_id) {
                $options[]  = JHTML::_('select.option', $item->id,$item->name);
            }
        }

        return $options;
    }
}
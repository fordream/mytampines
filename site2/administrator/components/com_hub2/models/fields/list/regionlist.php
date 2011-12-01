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
class JXFieldTypeList_RegionList extends JXFieldTypeMultiSelect {

    function _getOptions( &$node ) {
        $jApp = &JFactory::getApplication();

        $cid    = JRequest::getVar( 'cid', array(0), '', 'array' );
        $id     = JRequest::getVar( 'id', $cid[0], '', 'int' );
        $region_id  = $id;
        $view = JRequest::getVar('view','');
        if ($view !== 'region') {
            $region_id = 0;
        }

        $options = array();
        if ($node->attributes("addnone") !== "false") {
            $options[] = JHTML::_('select.option', '', '-- '.JText::_( 'None' ).' --');
        }
        $model      = Hub2DataModel::getInstance( 'region');
        $result	= $model->getPossibleParents($region_id);
        foreach ($result as $item) {
            if ((int)$item->id !== $region_id) {
                $name = $item->name;
                if ($item->level > 1) {
                    $name = str_repeat('- ',(int)$item->level-1).$item->name;
                }
                $options[]  = JHTML::_('select.option', $item->id, $name);
            }
        }

        return $options;
    }
}
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
 * Form field type object to return list of categories
 *
 */
class JXFieldTypeList_CategoryList extends JXFieldTypeMultiSelect {

    function _getOptions( &$node ) {
        $jApp = &JFactory::getApplication();

        $cid    = JRequest::getVar( 'cid', array(0), '', 'array' );
        $id     = JRequest::getVar( 'id', $cid[0], '', 'int' );
        $exl_id  = $id;
        $view = JRequest::getVar('view','');
        if ($view !== 'category') {
            $exl_id = 0;
        }

        $options = array();
        if ($node->attributes("addnone") !== "false") {
            $options[] = JHTML::_('select.option', '', '-- '.JText::_( 'None' ).' --');
        }
        $model      = Hub2DataModel::getInstance( 'category' );
        $result	= $model->getPossibleParents($exl_id);
        foreach ($result as $item) {
            if ((int)$item->id !== $exl_id) {
                $level = $item->level;
                if ($level == 0) {
                    $level = 1;
                }
                $options[]  = JHTML::_('select.option', $item->id,
                                 str_repeat('- ',(int)$level-1).$item->title);
            }
        }

        return $options;
    }
}
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
class JXFieldTypeList_SiteList extends JXFieldTypeMultiSelect {

    function _getOptions( &$node ) {
        $jApp = &JFactory::getApplication();

        $options = array();

        $model      = Hub2DataModel::getInstance( 'site');
        $result	= $model->getSites();
        foreach ($result as $item) {
            $options[]  = JHTML::_('select.option', $item->id,$item->name);
        }

        return $options;
    }
}
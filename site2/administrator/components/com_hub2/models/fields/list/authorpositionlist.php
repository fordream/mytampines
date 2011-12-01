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

require_once(JPATH_ROOT.DS.'components'.DS.'com_hub2'.DS.'models'.DS.'staticdata.php');
/**
 * form field type object to return list of author positions
 *
 */
class JXFieldTypeList_AuthorPositionList extends JXFieldTypeList
{
    function _getOptions( &$node ) {
        $jApp = &JFactory::getApplication();

        $model = Hub2ModelStaticData::getAnInstance();
        $items = $model->getItems('author.position', true);
        $options = array();
        foreach($items as $item) {
            $options[] = JHTML::_('select.option', $item->cvalue, $item->name);
        }
        array_unshift($options, JHTML::_('select.option', '', '-- '.JText::_( 'None' ).' --'));
        return $options;
    }
}
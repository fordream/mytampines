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

/**
 * Form field type object to return list of authors
 *
 */
class JXFieldTypeList_Authors extends JXFieldTypeList {

    function _getOptions( &$node ) {
        $jApp = &JFactory::getApplication();

        $model      = Hub2DataModel::getInstance( 'author' );
        $authors = $model->getList();
        $options = array();
        foreach($authors as $author) {
            $options[] = JHTML::_('select.option', $author->id, $author->fullname);
        }

        array_unshift($options, JHTML::_('select.option', '', '-- '.JText::_( 'None' ).' --'));
        return $options;
    }
}
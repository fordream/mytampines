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
 * form field type object to return list of site templates
 *
 */
class JXFieldTypeList_UserList extends JXFieldTypeList
{
    function _getOptions( &$node ) {
        $jApp = &JFactory::getApplication();

        $model		= Hub2DataModel::getInstance( 'author');
        $users = $model->getUsers();
        $options = array();
        foreach($users as $user) {
            $options[] = JHTML::_('select.option', $user->id, $user->name);
        }

        array_unshift($options, JHTML::_('select.option', '', '-- '.JText::_( 'None' ).' --'));
        return $options;
    }
}
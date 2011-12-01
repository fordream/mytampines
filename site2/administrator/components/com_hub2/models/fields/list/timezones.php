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
 * Form field type object to return list of timezones
 *
 */
class JXFieldTypeList_Timezones extends JXFieldTypeList {

    function _getOptions( &$node ) {

        $optons = array();
        $timeZones = DateTimeZone::listIdentifiers();
        foreach ( $timeZones as $timeZone ) {
            $options[] = JHTML::_('select.option',$timeZone);
        }

        array_unshift($options, JHTML::_('select.option', '', '-- '.JText::_( 'None' ).' --'));
        return $options;
    }
}
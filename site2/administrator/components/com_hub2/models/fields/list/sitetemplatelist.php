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
class JXFieldTypeList_SiteTemplateList extends JXFieldTypeList
{
    function _getOptions( &$node ) {
        $jApp = &JFactory::getApplication();

        $model		= Hub2DataModel::getInstance( 'site');
        $templates = $model->getTemplates();
        $options = array();
        foreach($templates as $template) {
            $options[] = JHTML::_('select.option', $template->id, $template->name);
        }

        array_unshift($options, JHTML::_('select.option', '', '-- '.JText::_( 'None' ).' --'));
        return $options;
    }
}
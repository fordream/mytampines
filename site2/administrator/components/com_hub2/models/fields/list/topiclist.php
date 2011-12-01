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
 * Form field type object to return list of regions
 *
 */
class JXFieldTypeList_TopicList extends JXFieldTypeList
{
    function _getOptions( &$node ) {
        $jApp = &JFactory::getApplication();

        $cid    = JRequest::getVar( 'cid', array(0), '', 'array' );
        $id     = JRequest::getVar( 'id', $cid[0], '', 'int' );
        $topic_id  = $id;
        $view = JRequest::getVar('view','');
        if ($view !== 'topic') {
            $topic_id = 0;
        }

        $options = array();
        $options[] = JHTML::_('select.option', '', '-- '.JText::_( 'None' ).' --');

        $model		= Hub2DataModel::getInstance( 'topic' );
        $result = $model->getPossibleParents($topic_id);
        foreach ($result as $item) {
            if ((int)$item->id !== $topic_id) {
                $level = $item->level;
                if ($level == 0) {
                    $level = 1;
                }
                $options[]  = JHTML::_('select.option', $item->id,
                                 str_repeat('- ',(int)$level-1).$item->name);
            }
        }

        return $options;
    }
}
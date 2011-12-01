<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
require_once(JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbar.php');
jximport('jxtended.form.helper');

class Hub2ViewTopic extends JView {
    function setToolbar() {
        $jApp = &JFactory::getApplication();
        JToolBarHelper::title( JText::_( 'ADMIN_TOPIC_TITLE_TOPIC' ),'logo');
        JToolBarHelper::trash('trash');
        JToolBarHelper::custom( 'edit', 'edit.png', 'edit_f2.png', 'Edit', true );
        JToolBarHelper::custom( 'edit', 'new.png', 'new_f2.png', 'New', false );
        if (!$jApp->isAdmin()) {
            $tool = &JToolbar::getInstance('toolbar');
            $this->assignRef('toolbar',$tool);
        }
    }
    /**
     * Display the Editing mode toolbar
     * @access  public
     */
    function setEditToolBar() {
        $jApp = &JFactory::getApplication();
        $cid    = JRequest::getVar( 'cid', array(0), '', 'array' );
        $id     = JRequest::getVar( 'id', $cid[0], '', 'int' );
        JToolBarHelper::title((($id>0)?JText::_( 'ADMIN_TOPIC_TITLE_EDIT' ):
        JText::_( 'ADMIN_TOPIC_TITLE_ADD' )), 'logo' );
        JToolBarHelper::save();
        JToolBarHelper::cancel();
        if (!$jApp->isAdmin()) {
            $tool = &JToolbar::getInstance('toolbar');
            $this->assignRef('toolbar',$tool);
        }
    }

    /**
     * Display the view
     *
     * @access	public
     */
    function display($tpl = null) {
        $jApp = &JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $lang->load('com_hub2_topic', JPATH_ADMINISTRATOR);
        // save current state
        $viewName = 'topic';
        $limit      = $jApp->getUserStateFromRequest( 'global.list.limit',
                     'limit', $jApp->getCfg( 'list_limit' ) );
        $limitstart = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.limitstart',
                     'limitstart',   0 );
        $search     = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.search',
                     'search',       '' );
        $orderCol   = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.ordercol',
                     'filter_order',     's.id' );
        $orderDirn  = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.orderdirn',
                     'filter_order_Dir', '' );
        $level      = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.level',
                     'level', 0 );

        $model = $this->getModel();
        $model->setState( 'limit',      $limit );
        $model->setState( 'limitstart', $limitstart );
        $model->setState( 'search',     $search );
        $model->setState( 'orderCol',   $orderCol );
        $model->setState( 'orderDirn',  $orderDirn );
        $model->setState( 'level',     $level );

        $state		= $model->getState();
        $this->assignRef( 'state', $state );

        $layout = $this->getLayout();

        if ($layout == 'edit') {
            $item	= $model->getItem();
            $this->assignRef('item', $item);

            // Some manual intervention for this view
            // to get the section to work correctly for a new item
            if (empty( $item )) {
                $item                   = new stdClass;
                $item->id               = 0;
                $item->name             = '';
            }
            $form	= &$model->getForm();
            $form->setName( 'adminForm' );
            $form->loadObject( $item );
            $this->assignRef( 'form', $form );
            $this->assignRef('errors',JRequest::getVar('errors'));
            $this->setEditToolBar();
        } else {
            $items = $model->getItems();
            $this->assignRef( 'items', $items );

            $options = array();
            $options[] = JHTML::_('select.option', 0, JText::_("- All -"));

            for ($i=1; $i < 10; $i++) {
                $options[]  = JHTML::_('select.option', $i, JText::_("Upto {$i} levels"));
            }

            $this->assignRef('levelopt',$options);

            $pagination = $model->getPagination();
            $this->assignRef('pagination',$pagination);
            $this->setToolbar();
        }
        if (!$jApp->isAdmin()) {
            $this->addTemplatePath(dirname(__FILE__).DS.'tmpl');
        }
        parent::display($tpl);
    }

}
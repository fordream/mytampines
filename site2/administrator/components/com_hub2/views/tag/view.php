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

class Hub2ViewTag extends JView
{
    function setToolbar() {
        $jApp = &JFactory::getApplication();
        JToolBarHelper::title( JText::_( 'ADMIN_TAG_TITLE_TAG' ), 'logo' );
        JToolBarHelper::trash( 'trash' );
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
        if (is_object( $this->item )) {
            $isNew          = ($this->item->id == 0);
        } else {
            $isNew          = true;
        }
        JToolBarHelper::title(
            JText::_(($isNew ? 'ADMIN_TAG_TITLE_ADD' :'ADMIN_TAG_TITLE_EDIT')), 'logo' );
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
     * @access  public
     */
    function display($tpl=null) {
        $jApp = &JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $lang->load('com_hub2_tag', JPATH_ADMINISTRATOR);
        // save current state
        $viewName = 'tag';
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
        $is_template      = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.is_template',
                     'is_template', -1 );

        $model = $this->getModel();
        $model->setState( 'limit',      $limit );
        $model->setState( 'limitstart', $limitstart );
        $model->setState( 'search',     $search );
        $model->setState( 'orderCol',   $orderCol );
        $model->setState( 'orderDirn',  $orderDirn );

        $state		= $model->getState();
        $this->assignRef( 'state', $state );

        $config = &JComponentHelper::getParams( 'com_hub2' );
        $this->assignRef( 'config', $config );

        $document   = &JFactory::getDocument();
        $this->assignRef( 'document',   $document );

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
            $this->setEditToolbar();
        } else {

            $this->setToolbar();
            $items = $model->getItems();
            $this->assignRef( 'items', $items );

            $options = array();
            $options[] = JHTML::_('select.option', '-1', "- ".JText::_("All")." -");
            $options[] = JHTML::_('select.option', 1, "- ".JText::_("Yes")." -");
            $options[] = JHTML::_('select.option', 0, "- ".JText::_("No")." -");

            $this->assignRef('is_template',$options);

            // setup the page navigation footer
            $pagination = &$model->getPagination();
            $this->assignRef( 'pagination', $pagination );

        }
        if (!$jApp->isAdmin()) {
            $this->addTemplatePath(dirname(__FILE__).DS.'tmpl');
        }
        parent::display($tpl);
    }

}
?>
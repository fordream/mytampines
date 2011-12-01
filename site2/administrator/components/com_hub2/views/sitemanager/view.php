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
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS.'siteparams.php');

class Hub2ViewSiteManager extends JView
{
    function setToolbar() {
        JToolBarHelper::title( JText::_( 'ADMIN_SITE_TITLE_SITE' ), 'logo' );
        JToolBarHelper::trash( 'trash' );
        JToolBarHelper::custom( 'edit', 'edit.png', 'edit_f2.png', 'Edit', true );
        JToolBarHelper::custom( 'edit', 'new.png', 'new_f2.png', 'New', false );
    }

    /**
     * Display the Editing mode toolbar
     * @access  public
     */
    function setEditToolBar() {
        if (is_object( $this->item )) {
            $isNew          = ($this->item->id == 0);
        } else {
            $isNew          = true;
        }
        JToolBarHelper::title(
            JText::_(  ($isNew ? 'ADMIN_SITE_TITLE_ADD' :'ADMIN_SITE_TITLE_EDIT')), 'logo' );
        JToolBarHelper::save();
        JToolBarHelper::cancel();
    }

    /**
     * Display the view
     *
     * @access  public
     */
    function display($tpl=null) {
        $jApp = &JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $lang->load('com_hub2_auther', JPATH_ADMINISTRATOR);
        $lang->load('com_hub2_site', JPATH_ADMINISTRATOR);
        // save current state
        $viewName = 'sitecreate';
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
            if (!$item->id) {
                // new item
                // set some default values
                $jApp = &JFactory::getApplication();
                $item->dbhost = $jApp->getCfg('host');
                $item->dbuser = $jApp->getCfg('user');
                $item->dbpassword = $jApp->getCfg('password');
                $item->adminemail = $jApp->getCfg('mailfrom');
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

            // setup the page navigation footer
            $pagination = &$model->getPagination();
            $this->assignRef( 'pagination', $pagination );

        }
        parent::display($tpl);
    }

}
?>

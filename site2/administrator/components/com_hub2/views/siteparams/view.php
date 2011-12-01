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
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS.
'site.php');

class Hub2ViewSiteParams extends JView {
    function setToolbar() {
        JToolBarHelper::title( JText::_( 'ADMIN_SITEPARAMS_TITLE' ), 'logo' );
        JToolBarHelper::trash( 'trash' );
        JToolBarHelper::custom( 'edit', 'edit.png', 'edit_f2.png', 'Edit', true );
        JToolBarHelper::custom( 'edit', 'new.png', 'new_f2.png', 'New', false );
        JToolBarHelper::custom( 'setsiteparamvalue', 'edit.png', 'edit_f2.png', 'Set Value', true );
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
        JText::_( ($isNew ? 'ADMIN_SITEPARAMS_TITLE_ADD' :
                    'ADMIN_SITEPARAMS_TITLE_EDIT')), 'logo' );
        JToolBarHelper::save();
        JToolBarHelper::cancel();
    }

    /**
     * Display the toolbar in setting value mode
     * @access  public
     */
    function setSaveValueToolBar() {
        JToolBarHelper::title(
        JText::_('ADMIN_SITEPARAMS_SETVALUE'), 'logo' );
        JToolBarHelper::custom( 'savesiteparamvalue', 'save.png', 'save_f2.png', 'Save', false );
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
        $lang->load('com_hub2_siteparam', JPATH_ADMINISTRATOR);
        // save current state
        $viewName = 'siteparams';
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

            // Some manual intervention for this view
            // to get the section to work correctly for a new item
            if (empty( $item )) {
                $item                   = new stdClass;
                $item->id               = 0;
                $item->name             = '';
            }

            $errors = JRequest::getVar('errors',null);
            $this->assignRef('errors',$errors);

            $values = $model->getState('values',null);
            if ($values) {
                // we are rediting an item due to a previous error
                // bind to values so we do not lose the new values for the user
                $item->bind($values);
            }

            $form	= &$model->getForm();
            $form->setName( 'adminForm' );
            $form->loadObject( $item );
            $this->assignRef( 'form', $form );

            $this->setEditToolbar();
        } else if ($layout == 'setvalue') {
            $item   = $model->getItem();
            $this->assignRef('item', $item);
            // need to get site details and set it to sites reference
            $siteModel = new Hub2ModelSite();
            $sites = $siteModel->getItems();
            $this->assignRef('sites', $sites);

            // need to get the values for each site and set it to values reference
            // check if we re-editing due to error on save
            // set to incoming values so the user entered values are not lost
            $values = $model->getState('values',null);
            if ($values == null) {
                // get the values from table since no incoming values
                $values = $model->getSiteParameterValues($item->id);
            }
            $this->assignRef('values', $values);

            $this->assignRef('errors',JRequest::getVar('errors'));
            $this->setSaveValueToolbar();
        } else {
            $this->setToolbar();
            $items = $model->getItems();
            $this->assignRef( 'items', $items );

            $siteCountForParam = $model->getSiteCountForEachParam();
            $this->assignRef('siteCountForParam',$siteCountForParam);

            $siteModel = new Hub2ModelSite();
            $sites = $siteModel->getItems();
            $siteCount = count($sites);
            $this->assignRef('siteCount',$siteCount);

            // setup the page navigation footer
            $pagination = &$model->getPagination();
            $this->assignRef( 'pagination', $pagination );
        }
        parent::display($tpl);
    }
}
?>
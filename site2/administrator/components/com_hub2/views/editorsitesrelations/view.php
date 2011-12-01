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

class Hub2ViewEditorSitesRelations extends JView {

    function setToolbar() {
        JToolBarHelper::title( JText::_( 'ADMIN_EDITOR_TITLE' ), 'logo' );
        JToolBarHelper::trash( 'trash' );
        JToolBarHelper::custom( 'edit', 'edit.png', 'edit_f2.png', 'Edit', true );
    }

    /**
     * Display the Editing mode toolbar
     * @access  public
     */
    function setEditToolBar() {
        JToolBarHelper::title( JText::_('ADMIN_EDITOR_TITLE_EDIT'), 'logo' );
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
        $lang->load('com_hub2_editorsitesrelations', JPATH_ADMINISTRATOR);
        // save current state
        $viewName = 'editorsites';

        $previousLimit = $jApp->getUserState('hub2.list.limit');

        $limit = $jApp->getUserStateFromRequest(
         'hub2.list.limit', 'limit', $jApp->getCfg( 'list_limit' ));

        // always do a get, never save limitstart in user state
        // else we ahve issues resetting it unless the limit is changed
        $limitstart = JRequest::getVar('limitstart',0,'GET');

        // In case limit has been changed, adjust limitstart to zero
        if ($limit !== $previousLimit) {
            $limitstart = 0;
        }

        $orderCol = $jApp->getUserStateFromRequest(
            'hub2.' . $viewName . '.ordercol', 'filter_order', 's.id');

        $orderDirn = $jApp->getUserStateFromRequest(
            'hub2.' . $viewName . '.orderdirn', 'filter_order_Dir', '');


        $search    = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.search',
                     'search',       '' );

        $model = $this->getModel();

        $model->setState( 'limit',      $limit );
        $model->setState( 'limitstart', $limitstart );
        $model->setState( 'search',     $search );
        $model->setState( 'orderCol',   $orderCol );
        $model->setState( 'orderDirn',  $orderDirn );

        $state		= $model->getState();
        $this->assignRef( 'state', $state );

        $layout = $this->getLayout();
        if ($layout == 'edit') {

            $item	= $model->getItem();

            if (!$item || empty($item)) {
                JError::raiseWarning('404','The user with given ID was not found');
                $jApp->redirect('index.php?option=com_hub2&view=editorsiterelations');
            }
            $item->site = $model->getSiteIds($item->id);

            $this->assignRef('item', $item);

            $form	= &$model->getForm();
            $form->setName( 'adminForm' );
            $form->loadObject( $item );
            $this->assignRef( 'form', $form );

            $profileform   = &$model->getForm('editorprofile');
            $profileform->loadObject($model->getEditorProfile());
            $this->assignRef( 'profileform', $profileform );

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
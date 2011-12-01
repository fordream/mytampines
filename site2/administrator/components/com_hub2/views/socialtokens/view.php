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


class Hub2ViewSocialTokens extends JView {
    function setToolbar() {
        JToolBarHelper::title( JText::_( 'ADMIN_SOCIALTOKENS_TITLE' ), 'logo' );
        JToolBarHelper::trash( 'trash' );
        JToolBarHelper::custom( 'edit', 'edit.png', 'edit_f2.png', 'Edit', true );
        JToolBarHelper::custom( 'editSocilalMediaFacebook', 'new.png',
        'new_f2.png', 'New Facebook', false );
        JToolBarHelper::custom( 'editSocilalMediaTwiter', 'new.png', 'new_f2.png',
        'New Twitter', false );
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
        JText::_( ($isNew ? 'ADMIN_SOCIALTOKENS_TITLE_ADD' :
                    'ADMIN_SOCIALTOKENS_TITLE_EDIT')), 'logo' );
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
        JToolBarHelper::custom( 'savesocialtokenvalue', 'save.png', 'save_f2.png', 'Save', false );
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
        $lang->load('com_hub2_socialtokens', JPATH_ADMINISTRATOR);
        // save current state
        $viewName = 'socialtokens';
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
            $mt    = JRequest::getVar( 'mediatype', array(0), '', 'array' );
            $this->assignRef('mediatype', $mt[0]);
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

            if($item->media_type == "Twitter") {
                $metadata = explode("^^^", $item->metadata);
                $item->access_token = $metadata[0];
                $item->access_token_secret = $metadata[1];
                $item->consumer_key = $metadata[2];
                $item->consumer_secret = $metadata[3];
                $this->assignRef('mediatype', $item->media_type);
            }
            elseif($item->media_type == "Facebook") {
                $this->assignRef('mediatype', $item->media_type);
            }

            $form	= &$model->getForm();
            $form->setName( 'adminForm' );
            $form->loadObject( $item );


            $this->assignRef( 'form', $form );
            $this->setEditToolbar();
        } else {
            $this->setToolbar();
            $items = $model->getItems();
            $this->assignRef( 'items', $items );
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
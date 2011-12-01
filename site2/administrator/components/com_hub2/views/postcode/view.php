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
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS.
'sitepostcoderelations.php');
jximport('jxtended.form.helper');

class Hub2ViewPostcode extends JView
{
    function setToolbar() {
        $jApp = &JFactory::getApplication();
        JToolBarHelper::title( JText::_( 'ADMIN_POSTCODE_TITLE_POSTCODE' ), 'logo' );
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
            JText::_(($isNew ? 'ADMIN_POSTCODE_TITLE_ADD' :'ADMIN_POSTCODE_TITLE_EDIT')), 'logo' );
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
        // save current state
        $lang = JFactory::getLanguage();
        $lang->load('com_hub2_postcode', JPATH_ADMINISTRATOR);

        $viewName = 'postcode';
        $limit      = $jApp->getUserStateFromRequest( 'global.list.limit',
                     'limit', $jApp->getCfg( 'list_limit' ) );
        $limitstart = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.limitstart',
                     'limitstart',   0 );
        $search     = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.search',
                     'search',       '' );
        $orderCol   = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.ordercol',
                     'filter_order',     's.id' );
        $orderDirn  = $jApp->getUserStateFromRequest( 'hub2.'.$viewName.'.orderdirn',
                     'filter_order_Dir', '');

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
                $item->postcode         = '';
            }
            $form	= &$model->getForm();
            $form->setName( 'adminForm' );
            $form->loadObject( $item );
            $this->assignRef( 'form', $form );

            // data to build multi-select for sites
            $site_model = Hub2DataModel::getInstance('site');

            $postcode_sites = null;
            if ($item->id) {
                $postcodeSiteModel = new Hub2ModelSitePostcodeRelations();
                $postcode_sites = $postcodeSiteModel->getItems($item->id);
            }
            $all_sites = $site_model->getSites();
            $all_site_options = array();

            foreach ($all_sites as $sitem) {
                $all_site_options[]  = JHTML::_('select.option', $sitem->id,
                $sitem->name);
            }
            $this->assignRef( 'postcode_sites', $postcode_sites );
            $this->assignRef( 'all_sites', $all_site_options );

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
        if (!$jApp->isAdmin()) {
            $this->addTemplatePath(dirname(__FILE__).DS.'tmpl');
        }
        parent::display($tpl);
    }

}
?>
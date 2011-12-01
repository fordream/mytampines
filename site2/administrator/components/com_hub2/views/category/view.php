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
require_once(JPATH_ROOT.DS.'components'.DS.'com_hub2'.DS.'views'.DS.'helpers'.DS.'media.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_hub2'.DS.'models'.DS.'contenttypes.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.'models'.DS.
'sitecategoryrelations.php');

class Hub2ViewCategory extends JView
{
    function setToolbar() {
        JToolBarHelper::title( JText::_( 'ADMIN_CATEGORY_TITLE_CATEGORY' ),'logo');
        JToolBarHelper::trash('trash');
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
            JText::_( ($isNew ? 'ADMIN_CATEGORY_TITLE_ADD' :'ADMIN_CATEGORY_TITLE_EDIT')), 'logo' );
        JToolBarHelper::save();
        JToolBarHelper::cancel();
    }

    /**
     * Display the view
     *
     * @access	public
     */
    function display($tpl = null) {
        $jApp = &JFactory::getApplication();
        // save current state

        /* language for the form */
        $lang = JFactory::getLanguage();
        $lang->load('com_hub2_category', JPATH_ADMINISTRATOR);
        /*   end   */
        $viewName = 'category';
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
                $item->title             = '';
            }
            // get the content type relations and set into the item
            $item->content_types = $model->getContentTypeMapping($item->id);

            $form	= &$model->getForm();
            $form->setName( 'adminForm' );
            $form->loadObject( $item );
            $this->assignRef( 'form', $form );

            // media selection for category
            $contentTypeModel = Hub2ModelContentTypes::getContentTypeInstance();
            $mediaField = $contentTypeModel->getMediaFieldForCategories();

            $mediaHelper = new Hub2ViewHelperMedia();
            $mediaHelper->onDisplayField($mediaField,$item);
            $this->assignRef("mediaHTML",$mediaField->html);

            // data to build multi-select for sites
            $site_model = Hub2DataModel::getInstance('site');

            $category_sites = null;
            if ($item->id) {
                $categorySiteModel = new Hub2ModelSiteCategoryRelations();
                $category_sites = $categorySiteModel->getItems($item->id);
            }
            $all_sites = $site_model->getSites();
            $all_site_options = array();

            foreach ($all_sites as $sitem) {
                $all_site_options[]  = JHTML::_('select.option', $sitem->id,
                $sitem->name);
            }
            $this->assignRef( 'existing_parent_id',$item->parent_id);
            $this->assignRef( 'category_sites', $category_sites );
            $this->assignRef( 'all_sites', $all_site_options );

            $this->assignRef('errors',JRequest::getVar('errors'));
            $this->setEditToolBar();
        } else {
            $items = $model->getItems(true);
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
        parent::display($tpl);
    }

}
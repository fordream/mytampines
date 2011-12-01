<?php
/*------------------------------------------------------------------------
# En Masse - Social Buying Extension 2010
# ------------------------------------------------------------------------
# By Matamko.com
# Copyright (C) 2010 Matamko.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.matamko.com
# Technical Support:  Visit our forum at www.matamko.com
-------------------------------------------------------------------------*/


defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."toolbar.enmasse.html.php");

class EnmasseViewDeal extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getWord('task');
					
		if($task == 'edit')
		{
			TOOLBAR_enmasse::_DEAL_NEW();
			
			$cid 	= JRequest::getVar( 'cid', array(0), '', 'array' );

			$row = JModel::getInstance('deal','enmasseModel')->getById($cid[0]);
			
			$this->assignRef( 'deal', $row );
			$this->assignRef( 'currencyPrefix', JModel::getInstance('setting','enmasseModel')->getCurrencyPrefix());
			$this->assignRef( 'currencyPostfix', JModel::getInstance('setting','enmasseModel')->getCurrencyPostfix());
			$this->assignRef( 'statusList', EnmasseHelper::$DEAL_STATUS_LIST);
			$this->assignRef( 'locationList', JModel::getInstance('location','enmasseModel')->listAllPublished());
			$this->assignRef( 'categoryList', JModel::getInstance('category','enmasseModel')->listAllPublished());
			$this->assignRef( 'salesPersonList', JModel::getInstance('salesPerson','enmasseModel')->listAllPublished());
			$this->assignRef( 'merchantList', JModel::getInstance('merchant','enmasseModel')->listAllPublished());	
			if($cid[0] != null)
			{
				$dealCategoryIdList = JModel::getInstance('dealcategory','enmasseModel')->getCategoryByDealId($cid[0]);
				//------------------------------
				// get category list of deal
				if(count($dealCategoryIdList)!=0)
					$dealCategoryList = JModel::getInstance('category','enmasseModel')->getCategoryListInArrId($dealCategoryIdList);
				else
				    $dealCategoryList = array();
				 // assign category list to template  
			    $this->assignRef('dealCategoryList',$dealCategoryList);
			    
			    $dealLocationIdList = JModel::getInstance('deallocation','enmasseModel')->getLocationByDealId($cid[0]);
			    //------------------------------
				// get location list of deal
			    if(count($dealLocationIdList)!=0)
			    	$dealLocationList = JModel::getInstance('location','enmasseModel')->getLocationListInArrId($dealLocationIdList);
			    else 
			       $dealLocationList = array();
			    $this->assignRef('dealLocationList',$dealLocationList);
			}
			else
			{
				$dealCategoryList = array();
				$this->assignRef('dealCategoryList',$dealCategoryList);
				$dealLocationList = array();
				$this->assignRef('dealLocationList',$dealLocationList);
			}		
		}
		elseif($task == 'add')
		{
			TOOLBAR_enmasse::_DEAL_NEW();		
			$this->assignRef( 'currencyPrefix', JModel::getInstance('setting','enmasseModel')->getCurrencyPrefix());
			$this->assignRef( 'currencyPostfix', JModel::getInstance('setting','enmasseModel')->getCurrencyPostfix());
			$this->assignRef( 'statusList', EnmasseHelper::$DEAL_STATUS_LIST);
			$this->assignRef( 'locationList', JModel::getInstance('location','enmasseModel')->listAllPublished());
			$this->assignRef( 'categoryList', JModel::getInstance('category','enmasseModel')->listAllPublished());
			$this->assignRef( 'salesPersonList', JModel::getInstance('salesPerson','enmasseModel')->listAllPublished());
			$this->assignRef( 'merchantList', JModel::getInstance('merchant','enmasseModel')->listAllPublished());	
			$dealCategoryList = array();
			$this->assignRef('dealCategoryList',$dealCategoryList);
			$dealLocationList = array();
			$this->assignRef('dealLocationList',$dealLocationList);
		}
		else //Task == Show
		{
			TOOLBAR_enmasse::_SMENU();
			$nNumberOfDeals = JModel::getInstance('deal','enmasseModel')->countAll();
			if($nNumberOfDeals==0)
			{
				TOOLBAR_enmasse::_DEAL_EMPTY();
			}
			else
			{
				TOOLBAR_enmasse::_DEAL();
			}
					
			$filter 	= JRequest::getVar('filter');
			$dealFromLocation = null;
			$dealFromCategory = null;
			
			if(!empty($filter['location_id']) && trim($filter['location_id'])!= '')
			 	$dealFromLocation = JModel::getInstance('dealLocation','enmasseModel')->getDealByLocationId($filter['location_id']);
			if(!empty($filter['category_id']) && trim($filter['category_id'])!= '')
			 	$dealFromCategory= JModel::getInstance('dealCategory','enmasseModel')->getDealByCategoryId($filter['category_id']);

			$dealList 		= JModel::getInstance('deal','enmasseModel')->search($filter['name'],$dealFromLocation,$dealFromCategory,$filter['published'],$filter['status']);
			/// load pagination
			$pagination =JModel::getInstance('deal','enmasseModel')->getPagination();
			$state =& $this->get( 'state' );
			// get order values
			$order['order_dir'] = $state->get( 'filter_order_dir' );
            $order['order']     = $state->get( 'filter_order' );
			for($i=0; $i < count($dealList); $i++)
			{
				$dealCategoryIdList = JModel::getInstance('dealcategory','enmasseModel')->getCategoryByDealId($dealList[$i]->id);
				$dealLocationIdList = JModel::getInstance('deallocation','enmasseModel')->getLocationByDealId($dealList[$i]->id);
				
				//----------------------------------------------
				// get list of category name
				if(count($dealCategoryIdList)!=0)
					$categoryList = JModel::getInstance('category','enmasseModel')->getCategoryListInArrId($dealCategoryIdList);
				else
				   $categoryList = null;

				   
				 //----------------------------------------------
				// get list of location name
				if(count($dealLocationIdList)!=0)
			    	$locationList = JModel::getInstance('location','enmasseModel')->getLocationListInArrId($dealLocationIdList);
				else
				   $locationList = null;
				   
				   
				if(count($locationList)!=0 && $locationList!=null)
					$dealList[$i]->location_name 		= $locationList;
				else 
					$dealList[$i]->location_name 		= null;
					
				if(count($categoryList)!=0 && $categoryList!=null)
					$dealList[$i]->category_name 		= $categoryList;
				else
				    $dealList[$i]->category_name 		= null;
				$dealList[$i]->sales_person_name 	= JModel::getInstance('salesPerson','enmasseModel')->retrieveName($dealList[$i]->sales_person_id);
			}

			$this->assignRef( 'filter', $filter);
			
			$this->assignRef( 'statusList', EnmasseHelper::$DEAL_STATUS_LIST);
			$this->assignRef( 'locationList', JModel::getInstance('location','enmasseModel')->listAllPublished());
			$this->assignRef( 'categoryList', JModel::getInstance('category','enmasseModel')->listAllPublished());
			$this->assignRef( 'dealList', $dealList );
			$this->assignRef('pagination', $pagination);
			$this->assignRef( 'order', $order );
		}
		parent::display($tpl);
	}

}
?>
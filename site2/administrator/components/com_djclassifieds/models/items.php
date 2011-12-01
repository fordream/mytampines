<?php
/**
* @version		1.1
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
* @copyright	Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
* 
* 
* DJ Classifieds is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Classifieds is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
* 
*/

defined ('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

/*Items Model*/


class DjclassifiedsModelItems extends JModel{
	
	var $_items;
	var $_categories;
	
	//budowa klauzuli where do zapytania przy filtrowaniu
	function _buildQueryWhere(){
		
		$option = JRequest::getVar('option','com_djclassifieds');
		$jApp = &JFactory::getApplication();
		$filter_state = $jApp->getUserStateFromRequest($option.'filter_state', 'filter_state');
		
		$where ='';
		
		if($filter_state =='P'){
			$where = 'i.published = 1';
		}
		elseif($filter_state == 'U'){
			$where = 'i.published = 0';
		}
		
		return ($where) ? ' AND '.$where : '';
	}
	
	
	//pobieranie itemow
	function getItems(){
		$order = JRequest::getVar('order');
		$cid = JRequest::getVar('cat_id');
		$ord_t = JRequest::getVar('ord_t','desc');
		$ord ='i.ordering';
		$c;
		if($order=='category'){
			$ord = ' c.name';
		}
		elseif($order=='id'){
			$ord = ' i.id';
		}
		elseif($order=='published'){
			$ord = ' i.published';
		}
		elseif($order=='name'){
			$ord = ' i.name';
		}elseif($order=='u_name'){
			$ord = ' u.name';
		}
		elseif($order=='intro_desc'){
			$ord = ' i.intro_desc';
		}
		elseif($order=='payed'){
			$ord = ' i.payed';
		}
		elseif($order=='first'){
			$ord = ' i.special';
		}
		elseif($order=='ordering'){
			$ord = ' i.ordering';
			JRequest::setVar('order','ordering');
		}
		
		if($ord_t == 'desc'){
			$ord .= ' DESC';
		}else{
			$ord .= ' ASC';
		}
		
		if($cid){
			$c = ' AND i.cat_id LIKE '.$cid.'';
		}else{
			$c='';
		}
		
		$find_name=JRequest::getVar('find_name');
		$fn='';
		if($find_name){
			$fn=" AND i.name LIKE '%".$find_name."%' ";
		}
		
		$limit = JRequest::getVar('limit', 25, '', 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		if(!$this->_items){
			
			$query = "SELECT i.* ,c.id as cat_id, c.name as cat_name, u.name as u_name FROM  #__djcf_items i ".
			"LEFT JOIN #__users u ON u.id = i.user_id ".
			"LEFT JOIN #__djcf_categories c ON c.id=i.cat_id ".
			"WHERE 1 ".$c." ".$fn." ORDER BY ".$ord." ";

			$this->_items = $this->_getList($query, $limitstart, $limit);

		}
		return $this->_items;
	}
	
	function getCount(){
		$cid = JRequest::getVar('cat_id');
		$wherecat = '';
		if($cid){
			$wherecat = ' WHERE cat_id LIKE '.$cid.'';
		}

		$find_name=JRequest::getVar('find_name');
		$fn='';
		if($find_name){
			$fn=" AND name LIKE '%".$find_name."%' ";
		}
		
		$db= &JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__djcf_items".$wherecat." ".$fn;

		$db->setQuery($query);
		$allelems=$db->loadResult();


		return $allelems;
	}
	
	//pobieranie kategorii do wylistowania - alfabetycznie
	function getCategories(){
		
		if(!$this->_categories){
			
			$query = "SELECT * FROM #__djcf_categories ORDER BY name";
			
			$this->_categories = $this->_getList($query,0,0);
		}
		
		return $this->_categories;
	}
	
	function pa(){
		$db= &JFactory::getDBO();
		$query = 'SELECT id FROM #__djcf_items WHERE payed=0 AND paypal_token ="" limit 10';

		$db->setQuery($query);
		$it=$db->loadObjectList();
		echo count($it);
		foreach($it as $i){
			$row = & JTable::getInstance('items','table');
			$row->load($i->id);
			$row->payed=1;
			$row->store();
		}
	}

	public function getSiteDetails() {
		 $ssiteModel = new Hub2ModelSite(); // Amol
                $result = $ssiteModel->getLocalSiteDetails(); //Amol
                return $result;
       }
	
}
?>

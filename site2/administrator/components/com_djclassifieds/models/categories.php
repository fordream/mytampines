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

/*Categories Model*/


class DjclassifiedsModelCategories extends JModel{
	
	var $_categories;
	var $_lists;
	
	//funkcja oddająca $lists
	
	function getLists(){
		
		return $this->_lists;
	}
	
	//budowa klauzuli where do zapytania przy filtrowaniu
	function _buildQueryWhere(){
		global $mainframe, $option;
		
		$filter_state = $mainframe->getUserStateFromRequest($option.'filter_state', 'filter_state');
		$filter_catid	= $mainframe->getUserStateFromRequest( 'filter_catid',	'filter_catid',	0,	'int' );
		
		$cats = $this->getCat();
		$this->_lists['catid'] = JHTML::_('select.genericlist',  $cats, 'filter_catid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'name', $filter_catid);
		$this->_lists['state'] = JHTML::_('grid.state', $filter_state, 'Published', 'Unpublished');
		
		$where =array();
		
		if($filter_state =='P'){
			$where[] = 'published = 1';
		}
		elseif($filter_state == 'U'){
			$where[] = 'published = 0';
		}
		if($filter_catid != 0){
			if($filter_catid=='-1'){
				$where[] = ' parent_id="0" ';
			}else{
				$where[] = ' parent_id="'.$filter_catid.'" ';	
			}			
		}
				
		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');
		if($filter_catid != 0){
			$where .= " ORDER BY ordering";
		}else{
			$where .= " ORDER BY parent_id";
		}
		
		return $where;
	}
	
	
	function getCategories(){
		
		if(!$this->_categories){
			
			$query = "SELECT * FROM #__djcf_categories".$this->_buildQueryWhere()."";

			$this->_categories = $this->_getList($query, 0, 0);

		}
		return $this->_categories;
	}
	function getCat(){
		$db= &JFactory::getDBO();
		$query = "SELECT id, name FROM #__djcf_categories ORDER BY name";

		$db->setQuery($query);
		
		$cats[] = JHTML::_('select.option', '0', '- '.JText::_('Select Parent Category').' -', 'id', 'name');
		$cats[] = JHTML::_('select.option', '-1', JText::_('Main Category'), 'id', 'name');
		$db->setQuery($query);
		return array_merge($cats, $db->loadObjectList());
	}
	
	function sendNotify(){
		global $mainframe, $option;
        $par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$mailfrom = $mainframe->getCfg( 'mailfrom' );
		$config =& JFactory::getConfig();    
		$href = substr(JURI::base(),0,-14);
		$fromname=$config->getValue('config.sitename').' - '.$href;
		
		$notify_days = (int)$par->get('notify_days');
		if($notify_days>0){
			$date_now =& JFactory::getDate();
			$date_now->_date += $notify_days*24*60*60;
			$date_all=$date_now->toMySQL();
			$date_all = explode(' ',$date_all);		

			$db= &JFactory::getDBO();
			$query = "SELECT i.id, i.cat_id, i.date_exp, i.name, i.user_id, u.email, u.name as u_name FROM #__djcf_items i, #__users u WHERE i.user_id = u.id AND i.notify=0 AND i.date_exp < '".$date_all[0]."' ";
			$db->setQuery($query);
			$items = $db->loadObjectList();
		
	
	//		$redirect= 'index.php?option=com_djclassifieds&view=showitem&cid='.$i->cat_id.'&id='.$i->id;	
	//		$redirect = JRoute::_($redirect);
	
	
	
		//echo '<pre>';
		//print_r($db);
		//print_r($items);die();
		//print_r($mainframe);die();
		
		foreach($items as $i){
			$mailto = $i->email;
			//$subject="Powiadomienie o wygaśnięciu ogłoszenia ".$i->name;
			$subject= sprintf ( JText::_( 'ITEM_EXP_SUB' ), $i->name);
		//	$message= sprintf ( JText::_( 'ITEM_EXP_EN' ), $i->name, $notify_days);
			$message = sprintf ( JText::_( 'ITEM_EXP' ), $i->name, $notify_days);
			$message .= sprintf ( JText::_( 'ITEM_EXP_SITE' ), $mainframe->getCfg('sitename'));
			//$message = "Ogłoszenie \" ".$i->name." \" wygaśnie za ".$notify_days." dni";

			JUtility::sendMail($mailfrom, $fromname, $mailto, $subject, $message);
	 
			$query = "UPDATE `#__djcf_items` SET notify=1 WHERE id=".$i->id;
			$db->setQuery($query);
			$db->query();
		}
			
			

		$db->setQuery($query);
		}
		return count($items);	
	}
}
?>
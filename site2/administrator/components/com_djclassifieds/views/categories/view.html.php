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

jimport('joomla.application.component.view');



class DjClassifiedsViewCategories extends JView

{

	function display($tpl = null)

	{

		$model =& $this->getModel();
		$nl = $model->getCategories();
		
		$this->assignRef('list',$nl);
		
		$lists = array();
		
		global $mainframe, $option;
		
		jimport('joomla.html.pagination');
		$pagination = new JPagination(count($nl), 0, count($nl));
		$this->assignRef('pagination',$pagination);
			
		$lists=$model->getLists();		
		$this->assignRef('lists', $lists);
		
		 $par = &JComponentHelper::getParams( 'com_djclassifieds' );
		 $notify_days = (int)$par->get('notify_days');
		 $this->assignRef('notify_days', $notify_days);
		 if($notify_days>0){
		 	$added = $model->sendNotify();
		 	$this->assignRef('added', $added);		 
		 }

		

		JToolBarHelper::title(JText::_('CATEGORIES'));

		JToolBarHelper::preferences('com_djclassifieds','500');

		JToolBarHelper::addNew('add',JText::_('ADD'));

		JToolBarHelper::editList('edit',JText::_('EDIT'));

		JToolBarHelper::deleteList(JText::_('REMOVE_ACCEPTATION'),'remove');

		

		

    	JSubMenuHelper::addEntry(JText::_('CATEGORIES'), 'index.php?option=com_djclassifieds', true);

		JSubMenuHelper::addEntry(JText::_('ITEMS'), 'index.php?option=com_djclassifieds&task=items', false);
		
		//JSubMenuHelper::addEntry(JText::_('NO_CATEGORY_ITEMS'), 'index.php?option=com_djclassifieds&task=nocategoryitems', false);

		parent::display($tpl);

	}

}
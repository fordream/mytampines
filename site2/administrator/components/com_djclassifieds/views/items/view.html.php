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



class DjclassifiedsViewItems extends JView

{

	function display($tpl = null)
	{
		$model =& $this->getModel();		
		$hubDetails = $model->getSiteDetails(); //Amol
		$this->assignRef('hub2Details', $hubDetails);
		
		$limit = JRequest::getVar('limit', 25, '', 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		jimport('joomla.html.pagination');
		$lista_count_items = $model->getCount();
		$pagination = new JPagination($lista_count_items, $limitstart, $limit);
		

		$this->assignRef('pagination',$pagination);

		$nl = $model->getItems();
		
		$cats = $model->getCategories();

		$this->assignRef('list',$nl);
		$this->assignRef('cats', $cats);
		
		

		

		JToolBarHelper::title(JText::_('ITEMS'));
		
		JToolBarHelper::custom('recreateThumbnails','move','move',JText::_('Recreate thumbnails'),true,true);
		
		JToolBarHelper::preferences('com_djclassifieds','500');
		
		JToolBarHelper::addNew('addItem',JText::_('ADD'));

		JToolBarHelper::editList('editItem',JText::_('EDIT'));

		JToolBarHelper::deleteList(JText::_('REMOVE_ACCEPTATION'),'removeItem');

		

		

    	JSubMenuHelper::addEntry(JText::_('CATEGORIES'), 'index.php?option=com_djclassifieds', false);

		JSubMenuHelper::addEntry(JText::_('ITEMS'), 'index.php?option=com_djclassifieds&task=items', true);
		
		//JSubMenuHelper::addEntry(JText::_('NO_CATEGORY_ITEMS'), 'index.php?option=com_djclassifieds&task=nocategoryitems', false);

		parent::display($tpl);

	}

}

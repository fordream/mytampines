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



class DJClassifiedsViewEdit extends JView

{

	function display($tpl = null)

	{

			$model =& $this->getModel();

		  $nl = $model->getCat();
		  $cats = $model->getCategories();

/* Amol Start */
		  $regions = $model->getHub2Regions();
		  $selectedIndex = $model->getSelectedHub2Regions(); //only for edit thing
		  $this->assignRef('regionlist',$regions);
		  $this->assignRef('selectedRegionlist',$selectedIndex);
		  $hubDetails = $model->getSiteDetails(); //Amol
		  $this->assignRef('hub2Details', $hubDetails);
/* Amol Ends */



	$this->assignRef('nl',$nl);
	$this->assignRef('list',$cats);
	


		JRequest::setVar('hidemainmenu',1);



		JToolBarHelper::title( JText::_('CATEGORIES'), 'generic.png');

		JToolBarHelper::save();

		JToolBarHelper::apply();

		JToolBarHelper::cancel();

		parent::display($tpl);

	}

}




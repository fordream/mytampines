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
require_once(dirname(__FILE__).DS.'helper.php');



$sort_list=$this->list;
$_list = new TreeNodeHelper();
if (JRequest::getVar('filter_catid') != 0){
	$ordering = true;
}else{
	 $ordering=false;
}

?>
<form action="index.php" method="post" name="adminForm">
	<table>
	<tr>
        		<td align="left" width="100%">
        			<?php
					if($this->notify_days>0 && $this->added>0){
						echo $this->added.' '.JText::_('notifications was send');
					}
					?>
        		</td>
				<td nowrap="nowrap">
					<?php
					echo $this->lists['catid'];
					echo $this->lists['state'];

						  //echo '<pre>';
						  //print_r($this);
					?>
				</td>
        	</tr>
			</table>
    <table class="adminlist">
        <thead>
            <tr>
                <th width="5%">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->list); ?>);"/>
                </th>
                <th width="5%">
                    <?php echo JText::_( 'id' ); ?>
                </th>
                <th width="25%">
                    <?php echo JText::_( 'NAME' ); ?>
                </th>
				<th width="30%">
                    <?php echo JText::_( 'Description' ); ?>
                </th>
				<th width="10%" align="center">
                    <?php echo JText::_( 'POSITION' ); ?>
					<?php if ($ordering)
							echo JText::_('ordering');
						?>
                </th>
                <th width="8%">
                    <?php echo JText::_( 'PARENT' ); ?>
                </th>
                <th width="5%">
                    <?php echo JText::_( 'Price' ); ?>
                </th>
				<th width="7%">
                    <?php echo JText::_( 'Autopublish' ); ?>
                </th>
                <th width="5%">
                    <?php echo JText::_( 'PUBLISHED' ); ?>
                </th>
            </tr>
        </thead>
		<?php
		$z=0;
		$_list->getSortList($sort_list,$sort_list,$z,$this->pagination);
		$i=0;
		/*foreach($sort_list as $list):
		$checked = JHTML::_('grid.id', ++$i, $this->id );
		$published=JHTML::_('grid.published', $list, $i);
		$i++;
		endforeach;*/

		?>
    </table>
    <input type="hidden" name="option" value="<?php echo $option; ?>" /><input type="hidden" name="task" value="" /><input type="hidden" name="boxchecked" value="0" />
</form>

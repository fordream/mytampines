<?php
/**
* @version		1.1
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Latests ads
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

defined( '_JEXEC' ) or die( 'Restricted access' );

$db =& JFactory::getDBO();
$query = "SELECT i.* ,c.id as cat_id, c.name as cat_name, u.name as u_name FROM #__djcf_categories c, #__djcf_items i ".
		"LEFT JOIN #__users u ON u.id = i.user_id WHERE c.id = i.cat_id ORDER BY i.date_start DESC,i.id DESC LIMIT ".$params->get('djcflatests_limit');

$db->setQuery( $query, 0);
?>
<table class="adminlist">
	<tr>
		<td class="title">
			<strong><?php echo JText::_( 'Ad Title' ); ?></strong>
		</td>
        <td class="title">
			<strong><?php echo JText::_( 'Ad ID' ); ?></strong>
		</td>
		<td class="title">
			<strong><?php echo JText::_( 'Category' ); ?></strong>
		</td>
        <td class="title">
			<strong><?php echo JText::_( 'User' ); ?></strong>
		</td>
	</tr>
<?php
$rows = $db->loadObjectList();
if ($rows)
{
	foreach ($rows as $row)
	{	
		echo '<tr>';
		echo '<td><a href="index.php?option=com_djclassifieds&task=editItem&cid[]='. $row->id .'&cat_id='.$row->cat_id.'">'. $row->name .'</a></td>';
		echo '<td width="30px">'. $row->id .'</td>';
		echo '<td>'. $row->cat_name .'</td>';
		if($row->user_id==0){
			echo '<td>---</td>';
		}else{
			echo '<td>'. $row->user_id .'-'.$row->u_name.'</td>';	
		}
		
		echo '</tr>';
	}
}
?>
</table>
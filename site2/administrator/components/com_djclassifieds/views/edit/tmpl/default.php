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

$editor =& JFactory::getEditor();
defined ('_JEXEC') or die('Restricted access');
require_once(dirname(__FILE__).DS.'helper.php');
$sort_list=$this->list;


/* <!-- Added By Amol -->*/
	$attribs = 'size="5" multiple="multiple"';
	$ctrl	 = 'regionMulti[]';
	$regList = JHTML::_(	'select.genericlist',
			   	$this->regionlist,
				$ctrl, $attribs,
				'value', 'text',
				$this->selectedRegionlist );
        require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'helpers'.DS.'hub2url.php');
        $hubURL = Hub2URLHelper::getHub2ExternalURL();
        $siteId = $this->hub2Details['id'];
/* <!-- Added By Amol --> */

$_list = new TreeNodeHelper();
if($this->nl->price==''){$this->nl->price=0;}
?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>
			<fieldset class="adminform">
			<legend><?php echo JText::_('DETAIL'); ?></legend>
				<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_('NAME');?>
					</td>
					<td>
						<input class="text_area" type="text" name="name"
							id="name" size="50" maxlength="250"
							value="<?php echo $this->nl->name; ?>" />
					</td>
				</tr>
<!-- Added By Amol -->
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_('Region');?>
					</td>
					<td>
						<?php echo $regList; ?>
					</td>
				</tr>
<!-- Added By Amol -->
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_('Price');?><br />
						(11.22)
					</td>
					<td>
						<input class="text_area" type="text" name="price"
							id="price" size="50" maxlength="250"
							value="<?php echo $this->nl->price/100; ?>" />
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_('PARENT_CATEGORY');?>
					</td>
					<td>
						<?php
							//stworzenie listy
							$optionss = array();
							$optionss=$_list->getSortList($sort_list,$sort_list);

							$main_tab = array();
							$main_tab[0]= JHTML::_('select.option', '0', JText::_('LEAVE_AS_MAIN_CAT'));
							$options = array();
							$options = array_merge_recursive ($main_tab, $optionss);
							echo JHTML::_('select.genericlist', $options, 'parent_id', null, 'value', 'text', $this->nl->parent_id);
						?>
					</td>
				</tr>
	            <tr>
	                <td width="100" align="right" class="key">
    	                <?php echo JText::_('Autopublish');?>
        	        </td>
            	    <td>
                	    <select name="autopublish">
                	    	<option value="0" <?php if($this->nl->autopublish=='0'){echo 'selected';}?> ><?php echo JText::_('Global');?></option>
							<option value="1" <?php if($this->nl->autopublish=='1'){echo 'selected';}?> ><?php echo JText::_('Yes');?></option>
							<option value="2" <?php if($this->nl->autopublish=='2'){echo 'selected';}?> ><?php echo JText::_('No');?></option>
                	    </select>
	                </td>
    	        </tr>
	            <tr>
	                <td width="100" align="right" class="key">
    	                <?php echo JText::_('DESCRIPTION');?>
        	        </td>
            	    <td>
                	    <textarea id="description" name="description" rows="5" cols="55" class="inputbox"><?php echo $this->nl->description; ?></textarea>
	                </td>
    	        </tr>
            <tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('IMAGES_INCLUDED');?>
                </td>
                <td>
                	<input type="hidden" name="icon_url" id="icon_url" value="<?php echo $this->nl->icon_url ?>" />
                    <?php
			if(!$image = $this->nl->icon_url){
				echo JText::_('NO_ICON_INCLUDED');
			}else{
			    $sciezka = str_replace('/administrator','',JURI::base());
				$sciezka .= '/components/com_djclassifieds/images/';
				$sciezka = $hubURL.'/components/com_djclassifieds/images/'; //Amol
				$sciezka .= $this->nl->icon_url;
				echo '<img src="'.$sciezka.'.ths.jpg" />';?>
				<input type="checkbox" name="del_icon" id="del_icon" value="1"/>
				<?php echo JText::_('CHECK_TO_DELETE');
			}
						?>
                </td>
            </tr>
			<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('ADD_ICON');?><br />
                    <?php echo JText::_('New icon overwrite existing image');?>
                </td>
                <td>
					<input type="file"  name="icon" />
                </td>
            </tr>
			</table>
			</fieldset>
			<input type="hidden" name="id" value="<?php echo $this->nl->id; ?>" />
			<input type="hidden" name="ordering" value="<?php echo $this->nl->ordering; ?>" />
			<input type="hidden" name="option" value="<?php echo $option;?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>
<script language="javascript" type="text/javascript">

	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
        var wal = 0;
		// do field validation
		if (form.name.value == ""){
			alert( "<?php echo JText::_( 'ALERT_CATEGORY_NAME', true ); ?>" );
			wal=1;
		}
		if (form.regionMulti.selectedIndex < 0 && wal == 0){
			alert( "<?php echo JText::_( 'Select Region', true ); ?>" );
			wal=1;
		}

		if(form.price.value != "0" && wal == 0){
			var price = form.price.value;
			var wzor = /^[0-9]+\.*[0-9]*$/;
			if (!wzor.test(price)) {
  				alert( "<?php echo JText::_( 'ALERT_PRICE_FORMAT', true ); ?>" );
				wal=1;
			}
		}
		if(wal==0){
			submitform( pressbutton );
		}
	}
</script>

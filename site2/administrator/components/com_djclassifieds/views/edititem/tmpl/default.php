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


jimport('joomla.media.images');

$document=& JFactory::getDocument();
//$js = JPATH_BASE.'/components/com_djclassidields/images/Moo.Form.js';
//$document->addScript($js);
JHTML::_( 'behavior.Mootools' );
require_once(dirname(__FILE__).DS.'helper.php');
$sort_list=$this->list;
$_list = new TreeNodeHelper();

$date_time =& JFactory::getDate();
$par = &JComponentHelper::getParams( 'com_djclassifieds' );
$exp_days = (int)$par->get('exp_days');

$date_time->_date = $date_time->_date+ $exp_days*24*60*60;
$date_exp= explode(' ',$date_time->toMySQL());
if($this->nl->date_exp==''){
	$this->nl->date_exp = $date_exp[0];
}
JHTML::_('behavior.calendar');

/* <!-- Added By Amol -->*/
require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'helpers'.DS.'hub2url.php');
$hubURL = Hub2URLHelper::getHub2ExternalURL();
$siteId = $this->hub2Details['id'];

	$attribs = 'size="5" multiple="multiple"';
	$ctrl	 = 'regionMulti[]';
	$regList = JHTML::_(	'select.genericlist',
			   	$this->regionlist,
				$ctrl, $attribs,
				'value', 'text',
				$this->selectedRegionlist );
/* <!-- Added By Amol --> */

?>
<form action="index.php" method="post" name="adminForm" id="adminForm"  enctype='multipart/form-data'>
    <fieldset class="adminform">
    	<center><img src='<?php echo JURI::base() ?>/components/com_djclassifieds/images/long_loader.gif' alt='LOADING' style='display: none;' id='upload_loading' /><div id="alercik"></div></center>
		<legend>
            <?php echo JText::_('DETAIL'); ?>
        </legend>
        <table class="admintable">
            <tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('NAME');?>
                </td>
                <td>
                    <input class="text_area" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->nl->name; ?>" />
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
                    <?php echo JText::_('PARENT_CATEGORY');?>
                </td>
                <td>
                    <?php
							$optionss = array();
							$optionss=$_list->getSortList($sort_list,$sort_list);
							$main_tab = array();
							$main_tab[0]= JHTML::_('select.option', '0', JText::_('UNCATEGORIZED'));
							$options = array();
							$options = array_merge_recursive ($main_tab, $optionss);
							echo JHTML::_('select.genericlist', $options, 'cat_id', null, 'value', 'text', $this->nl->cat_id);
						?>
                </td>
            </tr>
			<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('EXPIRATION DATE');?>

                </td>
                <td>
					<input class="inputbox" type="text" name="date_exp" id="date_exp" size="25" maxlenght="19" value = "<?php echo $this->nl->date_exp;?>"/>
					<input type="reset" class="button" value="..." onclick="return showCalendar('date_exp','%Y-%m-%d');" />
                </td>
            </tr>
			<tr>
				<td width="100" align="right" class="key">
					<?php echo JText::_('Promotion first');?>
				</td>
				<td>
				<input type="radio" name="special" value="1" <?php  if($this->nl->special==1){echo "checked";}?> /><?php echo JText::_('Yes'); ?>
				<input type="radio" name="special" value="0" <?php  if($this->nl->special==0){echo "checked";}?> /><?php echo JText::_('No'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<?php echo JText::_('Published');?>
				</td>
				<td>
				<input type="radio" name="published" value="1" <?php  if($this->nl->published==1){echo "checked";}?> /><?php echo JText::_('Yes'); ?>
				<input type="radio" name="published" value="0" <?php  if($this->nl->published==0){echo "checked";}?> /><?php echo JText::_('No'); ?>
				</td>
			</tr>
			<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('INTRO_DESCRIPTION');?>
 					<div id="ile">(<?php echo $par->get('introdesc_char_limit')-strlen($this->nl->intro_desc);?>)</div>
                </td>
                <td>
                	<?php //echo $editor->display( 'intro_desc', $this->nl->intro_desc, '100%', '180', '40', '10',false);
					?>
					 <textarea id="intro_desc" name="intro_desc" rows="5" cols="55" class="inputbox" onkeyup="checkt(this.form,<?php echo $par->get('introdesc_char_limit');?>);" onkeydown="checkt(this.form,<?php echo $par->get('introdesc_char_limit');?>);"><?php echo $this->nl->intro_desc; ?></textarea>
                </td>
            </tr>
            <tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('DESCRIPTION');?>
                </td>
                <td>
                    <?php //echo $editor->display( 'description', $this->nl->description, '100%', '250', '40', '10',true );
					?>
		            <textarea id="description" name="description" rows="25" cols="55" class="inputbox"><?php echo $this->nl->description; ?></textarea>
                </td>
            </tr>
			<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('Contact');?>
                </td>
                <td>
					 <textarea id="contact" name="contact" rows="4" cols="55" class="inputbox" ><?php echo $this->nl->contact; ?></textarea>
                </td>
            </tr>
			<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('Price');?>
                </td>
                <td>
                    <input class="text_area" type="text" name="price" id="price" size="50" maxlength="250" value="<?php echo $this->nl->price; ?>" />
                </td>
            </tr>
            <tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('IMAGES_INCLUDED');?>
                </td>
                <td>
                	<input type="hidden" name="image_url" id="image_url" value="<?php echo $this->nl->image_url ?>" />
                    <?php
							$images_count = 0;
							$images = array();
							if(!$image = $this->nl->image_url){
								echo JText::_('NO_IMAGES_INCLUDED');
							}else{
							$images=explode(';', substr($image,0,-1));
							for($i=0; $i<count($images); $i++){
								?>
			<?php
			        $sciezka = str_replace('/administrator','',JURI::base());
			        $sciezka .= '/components/com_djclassifieds/images/';
			         $sciezka = $hubURL.'/components/com_djclassifieds/images/'; //Amol
				$sciezka .= $images[$i];
			  ?>
			  <img src="<?php echo $sciezka;?>.th.jpg"/>
			  <input type="checkbox" name="usun[]" id="usun[]" value="<?php echo $images[$i];?>"/>
			  <?php echo JText::_('CHECK_TO_DELETE'); ?>
			  <br />
	<?php
							}
							}
						?>
                </td>
            </tr>
			<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('ADD_IMAGE');?>
                </td>
                <td>
                    <?php $image_urls = ""?>
					<div id="uploader">
					<input type="file"  name="image[]" />

					</div><br /><a href="#" onclick="addImage(); return false;" ><?php echo JText::_('ADD_IMG_LINK')?></a>
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="hidden" name="id" value="<?php echo $this->nl->id; ?>" />
	<input type="hidden" name="user_id" value="<?php echo $this->nl->user_id; ?>" />
	<input type="hidden" name="ordering" value="<?php echo $this->nl->ordering; ?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="items" />
	<input type="hidden" name="boxchecked" value="0" />
</form>

<script type="text/javascript">

function check(){
			if(document.adminForm.price.value.search(/^[0-9]+(\,{1}[0-9]{2})?$/i)){
				document.adminForm.price.style.backgroundColor='#F00000';
				$('price_alert').innerHTML = "<?php echo JText::_('ALERT_PRICE')?>";
				$('price_alert').setStyle('background','#f00000');
				$('price_alert').setStyle('color','#ffffff');
				$('price_alert').setStyle('font-weight','bold');
			}
			else{
				document.adminForm.price.style.backgroundColor='';
				$('price_alert').innerHTML = '';
				$('price_alert').setStyle('background','none');
			}
}

function addImage(){
	var inputdiv = document.createElement('input');
	inputdiv.setAttribute('name','image[]');
	inputdiv.setAttribute('type','file');

	var ni = $('uploader');

	ni.appendChild(document.createElement('br'))
	ni.appendChild(inputdiv);
}

function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancelItem') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.name.value == ""){
			alert( "<?php echo JText::_( 'ALERT_ITEM_NAME', true ); ?>" );
		//}
		//else if (form.producer_id.value == "0"){
		//	alert( "<?php echo JText::_( 'ALERT_ITEM_PRODUCER', true ); ?>" );
		}
		else if (form.cat_id.value <= 0){
			alert( "<?php echo JText::_( 'Select Category', true ); ?>" );
		} else if
		(form.regionMulti.selectedIndex < 0){
			alert( "<?php echo JText::_( 'Select Region', true ); ?>" );

		}

		else  {
			$('upload_loading').setStyle('display', 'block');
			$('alercik').innerHTML = "<?php echo JText::_('ALERT_SUBMIT');?>";
			submitform( pressbutton );
			document.adminForm.button.disabled=true;
		}
	}

function checkt(my_form,limit){
if(my_form.intro_desc.value.length<=limit)
{
	a=my_form.intro_desc.value.length;
	b=limit;
	c=b-a;
	document.getElementById('ile').innerHTML= '('+c+')';
}
else
{
	my_form.intro_desc.value = my_form.intro_desc.value.substring(0, limit);
}
}
</script>

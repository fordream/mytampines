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

$couponElements = $this->couponElementList;
//-------------------
// to re-define the link of server root
$temp_uri_arr =explode ('/',$_SERVER['REQUEST_URI'])  ;
$link = "";
 for($count = 0; $count < count($temp_uri_arr); $count++)
 {
 	if($temp_uri_arr[$count]== '')
 	{ }
 	else if($temp_uri_arr[$count] == 'administrator' )
 	{
 		break ;
 	}
 	else
 	{
 	$link.= '/';
 	$link.=$temp_uri_arr[$count];	
 	}
 }
 //-----------------------
 // import tooltip from library
JHTML::_('behavior.tooltip');
?>

<?php $option = 'com_enmasse';?>
<?php JHTML::_( 'behavior.modal' ); ?>
<div>
<form action="index.php" method="post" name="adminForm1">
<div class="width-100 fltrt">
<fieldset class="adminform"><legend><?php echo JText::_('COUPON_ELEMENTS');?></legend>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" width="5%"><?php echo JText::_('COUPON_ID');?></th>
			<th width="30%"><?php echo JText::_('COUPON_ELEMENT_NAME');?></th>
			<th width="5%"><?php echo JText::_('COUPON_ELEMENT_X');?></th>
			<th width="5%" ><?php echo JText::_('COUPON_ELEMENT_Y');?></th>
			<th width="5%"><?php echo JText::_('COUPON_ELEMENT_WIDTH');?></th>
			<th width="5%" ><?php echo JText::_('COUPON_ELEMENT_HEIGHT');?></th>
			<th width="5%"><?php echo JText::_('COUPON_ELEMENT_FONT_SIZE');?></th>
			<th></th>
		</tr>
	</thead>
	<?php
	for ($i=0; $i < count( $couponElements ); $i++)
	{
		$k = $couponElements % 2;
		$element = &$couponElements[$i];
	?>
	<tr class="<?php echo "row$k"; ?>">
		
		<td align="center"><?php echo $element->id; ?></td>
		<td><?php echo $element->name; ?></td>
		<td align="center"><?php echo $element ->x; ?></td>
		<td align="center"><?php echo $element ->y;?></td>
		<td align="center"><?php echo $element ->width; ?></td>
		<td align="center"><?php echo $element ->height;?></td>
		<td align="center"><?php echo $element ->font_size;?></td>
		<td align="center">
			<a href="index.php?option=<?php echo $option;?>&controller=coupon&task=editElement&elementId=<?php echo $element->id; ?>">Edit</a>
		</td>
	</tr>
	<?php
	} 
	?>
</table>
</fieldset>
<input type="hidden" name="option" value="<?php echo $option;?>" /> <input
	type="hidden" name="controller" value="coupon" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="boxchecked"
	value="0" />
</form>
<form action="index.php" method="post" name="adminForm">
<fieldset class="adminform"><legend><?php echo JText::_('COUPON_REVIEW');?></legend>
<table class="adminlist">
	<thead>
		<tr>
			<th width="40%"><?php echo JText::_('COUPON_REVIEW_MESAGE_1');?></th>
		</tr>
	</thead>
	<tr class="row0">
		<td align="center"><input class="text_area" type="text"
			name="coupon_bg_url" id="coupon_bg_url" size="50" maxlength="250" style="margin: 0 5px 0 0"
			value="<?php echo $this->couponBgUrl;?>" />
		
        <a rel="{handler: 'iframe', size: {x: 500, y: 400}}"
			href="<?php echo 'index.php?option=com_enmasse&controller=uploader&task=display&parentId=coupon_bg_url&couponbg=true'; ?>"
			class="modal" style="float:left;"><?php echo JText::_('COUPON_IMAGE_LINK');?></a> <i style="float:left; padding-left: 5px"><?php echo JText::_('COUPON_REVIEW_MESAGE_2');?></i>                    
		</td>

	</tr>
	<tr class="row1">
	    <td align="center">
	    	<iframe 
	    		width="800" 
	    		height="600" 
	    		src="<?php echo $link .'/index.php?option=com_enmasse&controller=coupon&task=generate&token='.EnmasseHelper::generateCouponToken('');?>">
	    	</iframe>
	    </td>
	</tr>
</table>
</fieldset>
<input type="hidden" name="option" value="<?php echo $option;?>" /> <input
	type="hidden" name="controller" value="coupon" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="boxchecked"
	value="0" />
</div>
</form>
</div>
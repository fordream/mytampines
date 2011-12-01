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
$row = $this -> element;
// load language pack
$language =& JFactory::getLanguage();
$base_dir = JPATH_SITE.DS.'components'.DS.'com_enmasse';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
    $extension = 'com_enmasse16';
}else{
    $extension = 'com_enmasse';
}
if($language->load($extension, $base_dir, $language->getTag(), true) == false)
{
	 $language->load($extension, $base_dir, 'en-GB', true);
}
?>
<script src="components/com_enmasse/script/jquery.js"></script>
<?php JHTML::_( 'behavior.modal' );

JHTML::_('behavior.tooltip') ;
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
?>
    <script language="javascript" type="text/javascript">
         $.noConflict();
        Joomla.submitbutton = function(pressbutton)
<?php
}else{
?>
	<script language="javascript" type="text/javascript">
	$.noConflict();
	submitbutton = function(pressbutton)
	<?php
	}
	?>
	{
	    var form = document.adminForm;
	    if (pressbutton == 'cancelElement')
	    {
	        submitform( pressbutton );
	        return;
	    }

	    var nXCoordinate = jQuery.trim(form.x.value);
	    var nYCoordinate = jQuery.trim(form.y.value);
	    var nWidth = jQuery.trim(form.width.value);
	    var nHeight = jQuery.trim(form.height.value);
	    var nFontSize = jQuery.trim(form.font_size.value);

        if (nXCoordinate=="" || isNaN(nXCoordinate) || nXCoordinate < 0)
	    {
	        alert( "<?php echo JText::_( 'COUPON_INVALID_X', true ); ?>" );
	    }    
	    else if (nYCoordinate=="" || isNaN(nYCoordinate) || nYCoordinate < 0)
	    {
	    	alert( "<?php echo JText::_( 'COUPON_INVALID_Y', true ); ?>" );
	    } 
	    else if (nWidth=="" || isNaN(nWidth) || nWidth <= 0)
	    {
	    	alert( "<?php echo JText::_( 'COUPON_INVALID_WIDTH', true ); ?>" );
	    }  	   
	    else if (nHeight=="" || isNaN(nHeight) || nHeight <= 0)
	    {
	    	alert( "<?php echo JText::_( 'COUPON_INVALID_HEIGHT', true ); ?>" );
	    } 
	    else if (nFontSize=="" || isNaN(nFontSize) || nFontSize <= 0)
	    {
	    	alert( "<?php echo JText::_( 'COUPON_INVALID_FONT', true ); ?>" );
	    }
	    else
	    {
	    	submitform( pressbutton );
	    }

	    
	}

	</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-100 fltrt">
<fieldset class="adminform"><legend>Details</legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key">Name</td>
		<td><?php echo $row->name;?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">X</td>
		<td><input class="text_area" type="text" name="x" id="x"
			size="50" maxlength="250" value="<?php echo $row->x;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Y</td>
		<td><input class="text_area" type="text" name="y" id="y"
			size="50" maxlength="250" value="<?php echo $row->y;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Width</td>
		<td><input class="text_area" type="text" name="width" id="width"
			size="50" maxlength="250" value="<?php echo $row->width;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Height</td>
		<td><input class="text_area" type="text" name="height" id="height"
			size="50" maxlength="250" value="<?php echo $row->height;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Font Size</td>
		<td><input class="text_area" type="text" name="font_size" id="font_size"
			size="50" maxlength="250" value="<?php echo $row->font_size;?>" /></td>
	</tr>

</table>
</fieldset>
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="option" value="com_enmasse" />
<input type="hidden" name="controller" value="coupon" />
<input type="hidden" name="task" value="" />
</div>
</form>
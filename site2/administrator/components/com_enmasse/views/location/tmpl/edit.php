<script src="components/com_enmasse/script/jquery.js"></script>
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

require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse".DS."helpers". DS ."DatetimeWrapper.class.php");
$row = $this -> location;
$option = 'com_enmasse';
JHTML::_('behavior.tooltip');
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
?>
    <script language="javascript" type="text/javascript">
        <!--
        Joomla.submitbutton = function(pressbutton)
<?php
}else{
?>
    <script language="javascript" type="text/javascript">
      
    submitbutton = function(pressbutton)
<?php
}
?>
        {
            var form = document.adminForm;
            if (pressbutton == 'cancel')
            {
                submitform( pressbutton );
                return;
            }
            sName = jQuery.trim(form.name.value.replace(/(<.*?>)/ig,""));
            // do field validation
            if (sName == "")
            {
                alert( "<?php echo JText::_( 'INVALID_NAME', true ); ?>" );
            }
            else
            {
            	 jQuery.post("index.php?option=com_enmasse&tmpl=component&controller=location&task=checkDuplicatedLocation", { locationName: sName },function(data) {
                 	   if(data == 'true' && sName!=form.tempName.value){
                 		  alert("<?php echo JText::_('LOCATION_NAME_DUPLICATED', true); ?>");
                      	   }
                 	   else
                 	   {
                 		  submitform( pressbutton );
                     	}
               	   
                  });
            }
        }       
        </script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-100 fltrt">
<fieldset class="adminform"><legend><?php echo JText::_('DETAIL')?></legend>
<table class="admintable" style="width: 100%">
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_LOCATION_NAME'),JTEXT::_('TOOLTIP_LOCATION_NAME_TITLE'), 
                    '', JTEXT::_('NAME'));?> *</td>
		<td><input class="text_area" type="text" name="name" id="name"
			size="50" maxlength="250" value="<?php echo htmlentities($row->name, ENT_QUOTES,"UTF-8");?>" />
			<input type="hidden"  name="tempName" value="<?php echo htmlentities($row->name, ENT_QUOTES,"UTF-8");?>" />
			</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_LOCATION_DESC'),JTEXT::_('TOOLTIP_LOCATION_DESC_TITLE'), 
                    '', JTEXT::_('DESC'));?></td>
		<td><textarea style="width: auto" type="text" name="description"
			id="description" maxlength="250" cols="36" rows="3"><?php echo $row->description;?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('PUBLISHED')?></td>
		<td><?php
		if ($row->published == null)
		{
			echo JHTML::_('select.booleanlist', 'published',
                          'class="inputbox"', 1);
		}
		else
		{
		echo JHTML::_('select.booleanlist', 'published',
                          'class="inputbox"', $row->published);
		}
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('CREATED_AT');?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->created_at); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('UPDATED_AT');?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->updated_at); ?></td>
	</tr>
</table>
</fieldset>
<input type="hidden" name="oldname" value="<?php echo $row->name;?>" />
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="location" />
<input type="hidden" name="task" value="" />
</div>
</form>
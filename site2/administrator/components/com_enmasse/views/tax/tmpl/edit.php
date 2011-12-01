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

$row = $this -> tax;
$option = 'com_enmasse';
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
        <!--
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
            // do field validation
            if (form.name.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_IN_TAX_NAME', true ); ?>" );
            }
            else if (form.tax_rate.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_IN_TAX_RATE.', true ); ?>" );
            }
            else if (isNaN(form.tax_rate.value))
            {
                alert( "<?php echo JText::_( 'TAX_RATE_SHOULD_BE_NUM', true ); ?>" );
            }
            else
            {
                submitform( pressbutton );
            }
        }
        //-->
        </script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<fieldset class="adminform"><legend>Details</legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key">Name</td>
		<td><input class="text_area" type="text" name="name" id="name"
			size="30" maxlength="250" value="<?php echo $row->name;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Rate</td>
		<td><input class="text_area" type="text" name="tax_rate" id="tax_rate"
			size="20" maxlength="250" value="<?php echo $row->tax_rate;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Published</td>
		<td><?php
		if ($row->published == null)
			echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', 1);
		else
			echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $row->published);
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Created At</td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->created_at); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Updated At</td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->updated_at); ?></td>
	</tr>
</table>
</fieldset>
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="tax" />
<input type="hidden" name="task" value="" />

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

$row = $this->emailTemplate;
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

            submitform( pressbutton );

        }
        //-->
        </script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-100 fltrt">
<fieldset class="adminform"><legend><?php echo JText::_('EMAIL_DETAIL');?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('SLUG_NAME');?></td>
		<td><?php echo $row->slug_name;?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('EMAIL_AVAILABLE_ATTRIBUTES');?></td>
		<td>
		<?php if ($row->avail_attribute):?>
		<?php echo $row->avail_attribute;?>
		<input type="hidden" name="avail_attribute"
            id="avail_attribute"
            value="<?php echo $row->avail_attribute;?>" />
		<?php else:?>
		<input class="text_area" type="text" name="avail_attribute"
			id="avail_attribute" size="50" maxlength="250"
			value="<?php echo $row->avail_attribute;?>" /></td>
		<?php endif;?>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('EMAIL_SUBJECT');?></td>
		<td><input class="text_area" type="text" name="subject" id="subject"
			size="50" maxlength="250" value="<?php echo $row->subject;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('EMAIL_CONTENT');?></td>
		<td><?php $editor =& JFactory::getEditor();
		echo $editor->display('content', $row->content, '800', '300', '50', '3');?>
		</td>
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
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="emailTemplate" />
<input type="hidden" name="task" value="" />
</div>
</form>
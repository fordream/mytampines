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

$row 	= $this->payGty;
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
                alert( "<?php echo JText::_( 'FILL_IN_MERCHANT_PAYMENT_GATEWAY_NAME', true ); ?>" );
            }
            else
            {
                submitform( pressbutton );
            }
        }
        //-->
        </script>
<script>
function addRow(nTableId)
{
	var txtCountAttribute = document.getElementById('count');
	var nIdOfNewAttribute = parseInt(txtCountAttribute.value);
	nIdOfNewAttribute = nIdOfNewAttribute + 1;
	txtCountAttribute.value = nIdOfNewAttribute;
	var attributeTable = document.getElementById(nTableId);
	var tblTr = document.createElement("tr");
	var tblTd1 = document.createElement("td");
	var txtAttributeName=document.createElement("input");
	txtAttributeName.type="text";
	txtAttributeName.name="attribute_name[" + nIdOfNewAttribute + "]";
	txtAttributeName.id="attribute_name[" + nIdOfNewAttribute + "]";
	txtAttributeName.size="50";
	txtAttributeName.maxlength="250";
	tblTd1.appendChild(txtAttributeName);
	var tblTd2 = document.createElement("td");
	var txtAttributeValue=document.createElement("input");
	txtAttributeValue.type="text";
	txtAttributeValue.name="attribute_value[" + nIdOfNewAttribute + "]";
	txtAttributeValue.id="attribute_value[" + nIdOfNewAttribute + "]";
	txtAttributeValue.size="50";
	txtAttributeValue.maxlength="250";
	tblTd2.appendChild(txtAttributeValue);
	tblTr.appendChild(tblTd1);
	tblTr.appendChild(tblTd2);
	attributeTable.appendChild(tblTr);
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-100 fltrt">
<fieldset class="adminform">
<legend><?php echo JText::_('PAY_DETAIL')?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><?php echo Jtext::_('PAY_NAME');?></td>
		<td><input class="text_area" type="text" name="name" id="name"
			size="50" maxlength="250" value="" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Class name</td>
		<td><input class="text_area" type="text" name="class_name" id="class_name"
			size="50" maxlength="250" value="" /></td>
	</tr>	
	<tr>
		<td width="100" align="right" class="key"><?php echo Jtext::_('PUBLISHED');?></td>
		<td><?php
			echo JHTML::_('select.booleanlist', 'published',
                          'class="inputbox"');
		?></td>
	</tr>
</table>
</fieldset>
<fieldset class="adminform">
<legend><?php echo JText::_('GATEWAY_SETTING');?></legend>
<input type="hidden" id="count" value="1"/>
<table class="admintable">
<tbody id="admintable">
	<tr>
		<th>Attribute name</th>
		<th>Attribute value</th>
	</tr>
	<tr>
		<td><input class="text_area" type="text" name="attribute_name[1]" 
           id="attribute_name[1]" size="50" maxlength="250" 
           value="" /></td>
		<td><input class="text_area" type="text" name="attribute_value[1]" 
           id="attribute_value[1]" size="50" maxlength="250" 
           value="" /></td>
	</tr>
</tbody>	
</table>
<a href="#" onclick="addRow('admintable'); return false;">Add attribute</a><br/>
Notice: The row with empty attribute name will not be saved.
</fieldset>
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="payGty" />
<input type="hidden" name="task" value="" />
</div>	 
</form>

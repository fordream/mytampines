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

$option = 'com_enmasse';
$row = $this->salesPerson;
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
?>
    <script src="components/com_enmasse/script/jquery.js"></script>
    <script language="javascript" type="text/javascript">
        <!--
        Joomla.submitbutton = function(pressbutton)
<?php
}else{
?>
    <script src="components/com_enmasse/script/jquery.js"></script>
    <script language="javascript" type="text/javascript">

	    
        <!--
        submitbutton = function(pressbutton)
<?php
}
?>
        {
    	    function validateEmail($email)
    	    {
    	    	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    	    	if( !emailReg.test( $email ) ) {
    	    		return false;
    	    	} else {
    	    		return true;
    	    	}
    	    }
    	    
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
            else if (form.phone.value != "" && isNaN(form.phone.value))
            {
            	alert( "<?php echo JText::_( 'NUMBERIC_PHONE', true ); ?>" );
            }
            else if (form.email.value != "" && validateEmail(form.email.value) == false)
            {
            	alert( "<?php echo JText::_( 'INVALID_EMAIL', true ); ?>" );
            }            
            else
            {
           	 jQuery.post("index.php?option=com_enmasse&tmpl=component&controller=salesPerson&task=checkDuplicatedName", { saleName : sName },function(data) {
                	   if(data == 'true' && sName!=form.tempName.value){
                		  alert("<?php echo JText::_('SALE_NAME_DUPLICATED', true); ?>");
                     	   }
                	   else
                	   {
                		  submitform( pressbutton );
                    	}
              	   
                 });
            }
        }

        function checkValidUser()
		{
        	var form = document.adminForm;
        	var ob = document.getElementById('invalid_msg');
        	 jQuery.post("index.php?option=com_enmasse&tmpl=component&controller=salesPerson&task=checkUserName&userName="+form.user_name.value,function(data) {
        		    if(data == 'invalid')
		  			{
		  				form.user_name.focus();
		  				document.getElementById('duplicated_msg').style.display = "none";
		  			    ob.style.display = "block";
		  			}
        		    else if(data == 'duplicated' && form.user_name.value!=form.temp_user_name.value)
        		    {
        		    	form.user_name.focus();
        		    	ob.style.display = "none";
        		    	document.getElementById('duplicated_msg').style.display = "block";
            		}
		  			else
		  			{
		  				ob.style.display = "none";
		  				document.getElementById('duplicated_msg').style.display = "none";
       		  	    }
         	   
            });
		}
        //-->
        </script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-100 fltrt">
<fieldset class="adminform"><legend><?php echo JText::_('SALE_PERSON_DETAIL');?></legend>
<table class="admintable" style="width: 100%">
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('SALE_PERSON_NAME');?> *</td>
		<td><input class="text_area" type="text" name="name" id="name"
			size="50" maxlength="250" value="<?php echo htmlentities($row->name, ENT_QUOTES,"UTF-8");?>" />
			<input type="hidden"  name="tempName" value="<?php echo htmlentities($row->name, ENT_QUOTES,"UTF-8");?>" />
			</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('SALE_PERSON_ADDRESS');?></td>
		<td><textarea style="width: auto" type="text" name="address"
			id="address" cols="36" rows="3"><?php echo $row->address;?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('SALE_PERSON_PHONE');?></td>
		<td><input class="text_area" type="text" name="phone" id="phone"
			size="50" maxlength="20" value="<?php echo $row->phone;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('SALE_PERSON_EMAIL');?></td>
		<td><input class="text_area" type="text" name="email" id="email"
			size="50" maxlength="250" value="<?php echo $row->email;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('PUBLISHED');?></td>
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
<fieldset class="adminform"><legend><?php echo JText::_('SALE_PERSON_USER_DETAIL');?></legend>
<table class="admintable" style="width: 100%">
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_SALE_PERSON_USERNAME'),JTEXT::_('TOOLTIP_SALE_PERSON_USERNAME_TITLE'), 
                    '', JTEXT::_('SALE_PERSON_USER_NAME'));?></td>
		<td><input class="text_area" type="text" name="user_name"
			id="user_name" size="50" maxlength="250"
			value="<?php echo $row->user_name;?>" onkeyup="checkValidUser()"/>
			<input type="hidden" name="temp_user_name" value="<?php echo $row->user_name;?>" />
			<div id='invalid_msg' style="display: none;color:red;">(<?php echo JText::_('INVALID_USER_NAME_MSG');?>)</div>
			<div id='duplicated_msg' style="display: none;color:red;">(<?php echo JText::_('USER_NAME_ALREADY_ASSIGNED');?>)</div>
			</td>
	</tr>
</table>
</fieldset>
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="salesPerson" />
<input type="hidden" name="task" value="" />
</div>
</form>
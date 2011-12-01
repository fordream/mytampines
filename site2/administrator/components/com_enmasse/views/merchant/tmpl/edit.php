
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

$row = $this -> merchant;
$option = 'com_enmasse';

	$emptyJOpt = JHTML::_('select.option', '', JText::_('') );
		
// create list location for combobox
	$salesPersonJOptList = array();
	array_push($salesPersonJOptList, $emptyJOpt);
	foreach ($this->salesPersonList as $item)
	{
		$var = JHTML::_('select.option', $item->id, JText::_($item->name) );
		array_push($salesPersonJOptList, $var);
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

			function isFloat(val)
			{
				if(!val || (typeof val != "string" || val.constructor != String))
				{
        			return false;
        	    }
        	    var isNumber = !isNaN(new Number(val));
        	    if(isNumber)
            	{
        	    	if(val.indexOf('.') != -1)
            	    {
        	        	return true;
        	    	}
            		else
					{
        	        	return false;
        	      	}
        	    }
        	    else
            	{
        	    	return false;
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
            else if (form.telephone.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_IN_MERCHANT_TELEPHONE', true ); ?>" );
            }
            else if (form.address.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_IN_MERCHANT_ADDRESS', true ); ?>" );
            }
            else if (form.postal_code.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_IN_MERCHANT_POSTAL_CODE', true ); ?>" );
            }
            else if (isNaN(form.google_map_lat.value))
            {
            	alert( "<?php echo JText::_( 'FLOAT_LATITIDE', true ); ?>" );
            }
            else if (isNaN(form.google_map_long.value))
            {
            	alert( "<?php echo JText::_( 'FLOAT_LONGTITUDE', true ); ?>" );
            }                                       
            else
            {
              	 jQuery.post("index.php?option=com_enmasse&tmpl=component&controller=merchant&task=checkDuplicatedName", { merchantName: sName },function(data) {
                 	   if(data == 'true' && sName!=form.tempName.value){
                   		  alert("<?php echo JText::_('MERCHANT_NAME_DUPLICATED', true); ?>");
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
        	 jQuery.post("index.php?option=com_enmasse&tmpl=component&controller=merchant&task=checkUserName&userName="+form.user_name.value,function(data) {
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
        
        
        </script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-100 fltrt">
<fieldset class="adminform"><legend><?php echo JText::_('MERCHANT_DETAIL');?></legend>
<table class="admintable" style="width: 100%">
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_MERCHANT_NAME'),JTEXT::_('TOOLTIP_MERCHANT_NAME_TITLE'), 
                    '', JTEXT::_('MERCHANT_NAME'));?> *</td>
		<td><input class="text_area" type="text" name="name" id="name"
			size="50" maxlength="250" value="<?php echo htmlentities($row->name, ENT_QUOTES,"UTF-8");?>" />
			<input type="hidden"  name="tempName" value="<?php echo htmlentities($row->name, ENT_QUOTES,"UTF-8");?>" />
			</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_MERCHANT_PHONE'), JTEXT::_('TOOLTIP_MERCHANT_PHONE_TITLE'), 
                    '', JTEXT::_('MERCHANT_TELEPHONE'));?> *</td>
		<td><input class="text_area" type="text" name="telephone"
			id="telephone" size="50" maxlength="20"
			value="<?php echo $row->telephone;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_MERCHANT_FAX'), JTEXT::_('TOOLTIP_MERCHANT_FAX_TITLE'),
                    '', JTEXT::_('MERCHANT_FAX'));?></td>
		<td><input class="text_area" type="text" name="fax" id="fax" size="50"
			maxlength="20" value="<?php echo $row->fax;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_MERCHANT_URL'),JTEXT::_('TOOLTIP_MERCHANT_URL_TITLE'), 
                    '', JTEXT::_('MERCHANT_WEB_URL'));?></td>
		<td><input class="text_area" type="text" name="web_url" id="web_url"
			size="50" maxlength="250" value="<?php echo $row->web_url;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_MERCHANT_ADDRESS'),JTEXT::_('TOOLTIP_MERCHANT_ADDRESS_TITLE'), 
                    '', JTEXT::_('MERCHANT_ADDRESS'));?> *</td>
		<td><textarea style="width: auto" type="text" name="address"
			id="address" cols="36" rows="3"><?php echo $row->address;?></textarea></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_MERCHANT_PCODE'),JTEXT::_('TOOLTIP_MERCHANT_PCODE_TITLE'), 
                    '', JTEXT::_('MERCHANT_POSTAL_CODE'));?> *</td>
		<td><input class="text_area" type="text" name="postal_code"
			id="postal_code" size="50" maxlength="20"
			value="<?php echo $row->postal_code;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_MERCHANT_LOGO'),JTEXT::_('TOOLTIP_MERCHANT_LOGO_TITLE'), 
                    '', JTEXT::_('MERCHANT_LOGO_URL'));?></td>
		<td><input class="text_area" type="text" name="logo_url" id="logo_url"
			size="50" maxlength="250" readonly="readonly" value="<?php echo $row->logo_url;?>" />
           
            <a rel="{handler: 'iframe', size: {x: 500, y: 400}}"
			href="<?php echo 'index.php?option=com_enmasse&controller=uploader&task=display&parentId=logo_url&parent=merchant'; ?>"
			class="modal"><?php echo JText::_('MERCHANT_LOGO_URL_LINK');?></a></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"> 
				<?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_MERCHANT_SALE_PERSON'),JTEXT::_('TOOLTIP_MERCHANT_SALE_PERSON_TITLE'), '', JTEXT::_('MERCHANT_SALE_PERSON')); ?>:
			</td>
		<td><?php
		echo JHTML::_('select.genericList',$salesPersonJOptList, 'sales_person_id', null , 'value','text',$row->sales_person_id );
		?></td>
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
		<td width="100" align="right" class="key"><?php echo JText::_('CREATED_AT')?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->created_at); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('UPDATED_AT')?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->updated_at); ?></td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('MERCHANT_USER_DETAIL')?></legend>
<table class="admintable" style="width: 100%">
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip('Enter Merchant\'s user name to access the frontend. This is the joomla user name assigned to him', 'Merchant User Name', 
                    '', JTEXT::_('MERCHANT_USER_NAME'));?></td>
		<td><input style="margin: 0 5px 0 0;" class="text_area" type="text" name="user_name"
			id="user_name" size="50" maxlength="250"
			value="<?php echo $row->user_name;?>" onkeyup="checkValidUser()" />
			<input type="hidden" name="temp_user_name" value="<?php echo $row->user_name;?>" />
			<div id='invalid_msg' style="display: none;color:red;">(<?php echo JText::_('INVALID_USER_NAME_MSG');?>)</div>
			<div id='duplicated_msg' style="display: none;color:red;">(<?php echo JText::_('USER_NAME_ALREADY_ASSIGNED');?>)</div>
			</td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_('MERCHANT_GOOGLE_MAP')?></legend>
<table class="admintable" style="width: 100%">
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip('Enter the Latitude of the google map to display for the merchant', 'Merchant Google Map Latitude', 
                    '', JTEXT::_('MERCHANT_GOOGLE_LATITUDE'));?></td>
		<td><input class="text_area" type="text" name="google_map_lat"
			id="google_map_lat" size="50" maxlength="250"
			value="<?php echo $row->google_map_lat;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip('Enter the Longtitude of the google map to display for the merchant', 'Merchant Google Map Longtitude', 
                    '', JTEXT::_('MERCHANT_GOOGLE_LONGTITUDE'));?></td>
		<td><input class="text_area" type="text" name="google_map_long"
			id="google_map_long" size="50" maxlength="250"
			value="<?php echo $row->google_map_long;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip('Enter the width of the google map', 'Merchant Google Map Width', 
                    '', JTEXT::_('MERCHANT_MAP_WIDTH'));?></td>
		<td><input class="text_area" type="text" name="google_map_width"
			id="google_map_width" size="50" maxlength="250"
			value="<?php
				if($row->google_map_width == null)
					echo 200;
				else 
					echo $row->google_map_width;
			?>" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JHTML::tooltip('Enter the height of the google map', 'Merchant Google Map Height', 
                    '', JTEXT::_('MERCHANT_MAP_HEIGHT'));?></td>
		<td><input class="text_area" type="text" name="google_map_height"
			id="google_map_height" size="50" maxlength="250"
			value="<?php
				if($row->google_map_height == null)
					echo 200;
				else 
					echo $row->google_map_height;
			?>" />
		</td>
	</tr>
</table>
</fieldset>
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="controller" value="merchant" />
<input type="hidden" name="task" value="" />
</div>
</form>
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

$emptyJOpt = JHTML::_('select.option', '', JText::_('') );

$row 			= $this->deal;
$merchantList 	= $this->merchantList;
$locationList 	= $this->locationList;
$categoryList 	= $this->categoryList;
$statusList 	= $this->statusList;
$option 		= 'com_enmasse';
global $mainframe;
JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');

//amol
	$isEdit=false;
	$readOnly = '';
	$isDisabled = '';
	if(JRequest::getWord('task') == 'edit' && $row->status && $row->status == 'On Sales'){	
		$isEdit=true;
		$readOnly = 'readonly="true"';
		$isDisabled = 'disabled="true"';
	} 
?>
<script language="javascript" type="text/javascript"> 
jQuery.noConflict();
</script>
<script language="javascript" type="text/javascript">        
        function moveOptions(from,to) {           
			  // Move them over
			  for (var i=0; i<from.options.length; i++) {
				var o = from.options[i];
				if (o.selected) {
				  to.options[to.options.length] = new Option( o.text, o.value, false, false);
				}
			  }
			  // Delete them from original
			  for (var i=(from.options.length-1); i>=0; i--) {
				var o = from.options[i];
				if (o.selected) {
				  from.options[i] = null;
				}
			  }
			  from.selectedIndex = -1;
			  to.selectedIndex = -1;
        }
        function dateGen(str)
        {
        	var year  = parseInt(str.substring(0,4),10);
        	var month  = parseInt(str.substring(5,7),10);
        	var day  = parseInt(str.substring(8,10),10);
        	var date = new Date(year, month, day);
        	return date.getTime();
        }
        function currDate()
        {
        	 var currentTime = new Date();
             var year = currentTime.getFullYear();
             var month = currentTime.getMonth() + 1;
             var day = currentTime.getDate();
             var today = "";
             if(month > 9 && day > 9 )
             {today = year+"-"+month+"-"+day;}
             else if(month > 9)
             {today = year+"-"+month+"-0"+day;}
             else if(day > 9)
             {today = year+"-0"+month+"-"+day;}
             else
             {today = year+"-0"+month+"-0"+day;}
             return today;
        }
<?php        
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
?>
        Joomla.submitbutton = function(pressbutton)
<?php
}else{
?>
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
            sName = jQuery.trim(form.name.value.replace(/(<.*?>)/ig,""));
            if (sName == "")
            {
                alert( "<?php echo JText::_( 'INVALID_NAME', true ); ?>" );
            }
            else if (form.origin_price.value == "" || form.origin_price.value == 0)
            {
                alert( "<?php echo JText::_( 'FILL_IN_DEAL_ORIG_PRICE', true ); ?>" );
            }
            else if (form.min_needed_qty.value < 0)
            {
                alert( "<?php echo JText::_( 'DEAL_MIN_QTY_NOT_NEGATIVE', true ); ?>" );
            }             
            else if (form.price.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_IN_DEAL_PRICE', true ); ?>" );
            }
            else if (parseInt(form.price.value) > parseInt(form.origin_price.value))
            {
            	alert( "<?php echo JText::_( 'PRICE_NOT_GREATER_ORIG_PRICE', true ); ?>" );
            }   
            else if (isNaN(form.origin_price.value))
            {
                alert( "<?php echo JText::_( 'NUMBERIC_DEAL_ORIG_PRICE', true ); ?>" );
            }                     
            else if (isNaN(form.price.value))
            {
                alert( "<?php echo JText::_( 'NUMBERIC_DEAL_PRICE', true ); ?>" );
            }
            else if (form.price.value < 0)
            {
                alert( "<?php echo JText::_( 'DEAL_PRICE_NOT_NEGATIVE', true ); ?>" );
            }
            else if (form.origin_price.value < 0)
            {
                alert( "<?php echo JText::_( 'DEAL_ORIG_PRICE_NOT_NEGATIVE', true ); ?>" );
            }                                 
            else if (form.start_at.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_IN_DEAL_START_DATE', true ); ?>" );
            }
            else if (form.end_at.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_IN_DEAL_END_DATE', true ); ?>" );
            }
            else if(dateGen(form.start_at.value) > dateGen(form.end_at.value))
            {
            	alert( "<?php echo JText::_( 'DEAL_END_GREATER_START', true ); ?>" ); 
            }
		<?php if(!$isEdit) { ?>   
            else if(dateGen(form.start_at.value) < dateGen(currDate()))
            {
                             
						alert( "<?php echo JText::_( 'DEAL_START_LESS_TODAY', true ); ?>" ); 		 		
				
            }
		<?php } ?>
            else if (form.min_needed_qty.value == "")
            {
                alert( "<?php echo JText::_( 'FILL_IN_DEAL_MIN_QTY', true ); ?>" );
            }
            else if(form.max_buy_qty.value < -1){
                alert("<?php echo JText::_('MAX_BUY_QTY_LESS_THAN_ZERO', true)?>");
            }
            else if(form.max_coupon_qty.value < -1){
                alert("<?php echo JText::_('MAX_COUPON_QTY_LESS_THAN_ZERO', true)?>");
            }            
            else if(isNaN(form.max_buy_qty.value)){
                alert("<?php echo JText::_('NUMBER_MAX_BUY_QTY', true)?>");
            }
            else if(jQuery.trim(form.max_buy_qty.value) == "")   
            {
                alert("<?php echo JText::_('EMPTY_MAX_BUY_QTY', true)?>");                
            }  
            else if(isNaN(form.max_coupon_qty.value)){
                alert("<?php echo JText::_('NUMBER_MAX_COUPON_QTY', true)?>");
            }
            else if(jQuery.trim(form.max_coupon_qty.value) == "")   
            {
                alert("<?php echo JText::_('EMPTY_MAX_COUPON_QTY', true)?>");                
            }                         
            else if (isNaN(form.min_needed_qty.value))
            {
                alert( "<?php echo JText::_( 'NUMBERIC_DEAL_MIN_QTY', true ); ?>" );
            }
            else if(document.adminForm['location_id[]'].options.length ==0)
            {
                  alert ("<?php echo JText::_('PLEASE_CHOOSE_LOCATION_FOR_DEAL', true);?>");
            }
            else if(document.adminForm['pdt_cat_id[]'].options.length ==0)
            {
                  alert ("<?php echo JText::_('PLEASE_CHOOSE_CATEGORY_FOR_DEAL', true);?>");
            }
            else
            {
//            	allSelected(document.adminForm['location_id[]']);
//            	allSelected(document.adminForm['pdt_cat_id[]']);
//                submitform( pressbutton );
//                return;
           	 jQuery.post("index.php?option=com_enmasse&tmpl=component&controller=deal&task=checkDuplicatedDeal", { dealName: sName },function(data) {
    	           if(data == 'true' && sName!=form.tempName.value){
                		  alert("<?php echo JText::_('DEAL_NAME_DUPLICATED', true); ?>");
                     	   }
                	   else
                	   {
                		   allSelected(document.adminForm['location_id[]']);
                           allSelected(document.adminForm['pdt_cat_id[]']);
                           submitform( pressbutton );
                           return;
                    	}
              	   
                 });
            }
            
        }

        function allSelected(element) {
			   for (var i=0; i<element.options.length; i++) {
					var o = element.options[i];
					o.selected = true;

				}
			 }
        
        </script>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
<div class="width-100 fltrt">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'DEAL_DETAILS' ); ?></legend>
<table class="admintable" style="width: 100%">	<tr>
		<td width="100" align="right" class="key"><label for="name"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_NAME'), JTEXT::_('TOOLTIP_DEAL_NAME_TITLE'), '', JTEXT::_('DEAL_NAME')); ?>
		* </label></td>
		<td><input class="required" type="text" <?php echo $readOnly; ?> name="name" id="name"
			size="50" maxlength="250" value="<?php echo htmlentities($row->name, ENT_QUOTES,"UTF-8");?>" />
			<input type="hidden"  name="tempName" value="<?php echo htmlentities($row->name, ENT_QUOTES,"UTF-8");?>" />
			</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="description"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_DESCRIPTION'),JTEXT::_('TOOLTIP_DEAL_DESCRIPTION_TITLE'), '', JTEXT::_('DEAL_DESC')); ?>
		</label></td>
		<td><?php 
		$editor =& JFactory::getEditor();
		echo $editor->display('description', $row->description, '800', '300', '50', '3');
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="short_desc"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_S_DESCRIPTION'),JTEXT::_('TOOLTIP_DEAL_S_DESCRIPTION_TITLE'), '', JTEXT::_('DEAL_SHORT_DESC')); ?>
		</label></td>
		<td><textarea style="width: auto" name="short_desc" id="short_desc"
			rows="3" cols="36"><?php echo $row->short_desc;?></textarea></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="price"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_O_PRICE'),JTEXT::_('TOOLTIP_DEAL_O_PRICE_TITLE'), '', JTEXT::_('DEAL_ORIGINAL_PRICE')); ?>
		* </label></td>
		<td><?php echo $this->currencyPrefix;?> <input class="text_area"
			type="text" name="origin_price" <?php echo $readOnly; ?> id="origin_price" size="50"
			maxlength="250" value="<?php echo $row->origin_price;?>" /> <?php echo $this->currencyPostfix;?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="price"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_C_PRICE'),JTEXT::_('TOOLTIP_DEAL_C_PRICE_TITLE'), '', JTEXT::_('DEAL_PRICE')); ?>
		* </label></td>
		<td><?php echo $this->currencyPrefix;?> <input class="text_area"
			type="text" <?php echo $readOnly; ?> name="price" id="price" size="50" maxlength="250"
			value="<?php echo $row->price;?>" /> <?php echo $this->currencyPostfix;?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="pic_dir"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_URL'),JTEXT::_('TOOLTIP_DEAL_URL_TITLE'), '', JTEXT::_('DEAL_PIC_URL')); ?>
		</label></td>
		<td><input class="text_area" type="text" name="pic_dir" id="pic_dir"
			size="50" maxlength="250" value="<?php echo $row->pic_dir;?>" readonly/> 

		<a rel="{handler: 'iframe', size: {x: 500, y: 400}}"
			href="<?php echo 'index.php?option=com_enmasse&controller=uploader&task=display&parentId=pic_dir&parent=deal'; ?>"
			class="modal"><?php echo JText::_('DEAL_PIC_URL_LINK');?></a></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="start_at"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_START_DATE'), JTEXT::_('TOOLTIP_DEAL_START_DATE_TITLE'), '', JTEXT::_('DEAL_START_AT')); ?>
		* </label></td>
		<td>
            <?php
                $version = new JVersion;
                $joomla = $version->getShortVersion();
                if(substr($joomla,0,3) == '1.6'){
					//Amol
            	    echo JHTML::_('calendar',$row->start_at, 'start_at','start_at','%Y-%m-%d 00:00:00' , array('size'=>50, 'autocomplete'=>'off'));
                }else{
            ?>
                    <input type="text" name="start_at" id="start_at" value="<?php echo $row->start_at ?>" size="50" autocomplete="off"/> 
                    <input type="reset" class="button" value="..." onclick="return showCalendar('start_at','%Y-%m-%d 00:00:00');" />
            <?php
                }
            ?>
        </td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="end_at"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_END_DATE'), JTEXT::_('TOOLTIP_DEAL_END_DATE_TITLE'), '', JTEXT::_('DEAL_END_AT')); ?>
		* </label></td>
		<td>
            <?php
                $version = new JVersion;
                $joomla = $version->getShortVersion();
                if(substr($joomla,0,3) == '1.6'){
            	    echo JHTML::_('calendar',$row->end_at, 'end_at','end_at','%Y-%m-%d 00:00:00' , array('size'=>50, 'autocomplete'=>'off'));
                }else{
            ?>
                    <input type="text" name="end_at" id="end_at"
            			value="<?php echo $row->end_at ?>" size="50" autocomplete="off"> <input type="reset"
            			class="button" value="..."
            			onclick="return showCalendar('end_at','%Y-%m-%d 00:00:00');" />
            <?php
                }
            ?>
        </td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="min_need_qty"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_MIN_QTY'), JTEXT::_('TOOLTIP_DEAL_MIN_QTY_TITLE'), '', JTEXT::_('DEAL_MIN_QUANTITY')); ?>
		* </label></td>
		<td><input size="50" type="text" name="min_needed_qty" id="min_needed_qty"
			value="<?php echo $row->min_needed_qty ?>"></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="max_buy_qty"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_MAX_BUY_QTY'), JTEXT::_('TOOLTIP_DEAL_MAX_BUY_QTY_TITLE'), '', JTEXT::_('DEAL_MAX_BUY_QUANTITY')); ?>
		</label></td>
		<td><input size="50" type="text" name="max_buy_qty" id="max_buy_qty"
			value="<?php if(!empty($row->max_buy_qty) && $row->max_buy_qty!=0) echo $row->max_buy_qty ; else echo '-1'; ?>">
			 <i><?php  echo JText::_('MSG_MAX_BUY_QTY_DES');?></i>
			</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="max_coupon_qty"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_MAX_COUPON_QTY'), JTEXT::_('TOOLTIP_DEAL_COUPON_BUY_QTY_TITLE'), '', JTEXT::_('DEAL_MAX_COUPON_QUANTITY')); ?>
		</label></td>
		<td><input size="50" type="text" name="max_coupon_qty" id="max_coupon_qty"
			value="<?php if(!empty($row->max_coupon_qty) && $row->max_coupon_qty!=0) echo $row->max_coupon_qty ; else echo '-1'; ?>">
		    <i><?php  echo JText::_('MSG_MAX_COUPON_QTY_DES');?></i>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="merchant_id"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_MERCHANT'), JTEXT::_('TOOLTIP_DEAL_MERCHANT_TITLE'), '', JTEXT::_('DEAL_MERCHANT')); ?>
		</label></td>
		<td><?php
		$merchantJOptList = array();
		array_push($merchantJOptList, $emptyJOpt);
		foreach ($this->merchantList as $item)
		{
			$var = JHTML::_('select.option', $item->id, $item->name );
			array_push($merchantJOptList, $var);
		}
			
		echo JHTML::_('select.genericList', $merchantJOptList, 'merchant_id', null , 'value','text',$row->merchant_id );
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="pdt_cat_id"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_CATEGORY'),JTEXT::_('TOOLTIP_DEAL_CATEGORY_TITLE'), '', JTEXT::_('DEAL_CATEGORY')); ?>
		 *  </label></td>
		<td>
		     <div>
			       <div style="float: left;">
			            <div><b><?php echo JText::_('AVAILABLE_CATEGORY');?></b></div>
			            <div>
						<?php
						// create list location for combobox
						$categoryJOptList = array();
						
							if(count($this->dealCategoryList)!=0)
							{
								$categoryList = $this->categoryList;
								$dealCategoryList = $this->dealCategoryList;
								for ( $i=0; $i < count($categoryList); $i++)
								{
									$available = false;
									for( $x=0 ; $x < count($dealCategoryList); $x++)
									{
										if($categoryList[$i]->id == $dealCategoryList[$x]->id )
										{
											$available = true;
										}
									}
									if(!$available)
									{
									$var = JHTML::_('select.option', $categoryList[$i]->id, $categoryList[$i]->name );
									array_push($categoryJOptList, $var);
									}
								}
							}
							else
							{
								foreach ($this->categoryList as $item)
								{
									$var = JHTML::_('select.option', $item->id, $item->name );
									array_push($categoryJOptList, $var);
								}
							}
						
							
						echo JHTML::_('select.genericList',$categoryJOptList, 'pdt_cat_list', 'class="inputbox" size="10"  onDblClick="moveOptions(document.adminForm.pdt_cat_list, document.adminForm[\'pdt_cat_id[]\'])" multiple="multiple"', 'value','text',null );
						?>
						</div>
					</div>
					<div style="float: left; padding: 40px 10px 0px 10px">
							<input style="width: 50px" type="button" name="Button" value="&gt;" onClick="moveOptions(document.adminForm.pdt_cat_list, document.adminForm['pdt_cat_id[]']);" />
	                            <br /><br />
	                        <input style="width: 50px" <?php echo $isDisabled; ?> type="button" name="Button" value="&lt;" onClick="moveOptions(document.adminForm['pdt_cat_id[]'],document.adminForm.pdt_cat_list)" />
	                            <br /><br />
					</div>
					<div style="float: left;">
						<div><b><?php echo JText::_('CURRENT_CATEGORY');?></b> </div>
						<div>
					        <?php 
					            $dealCategoryJOptList = array();
					            if(!empty($this->dealCategoryList))
					            {
					            	foreach($this->dealCategoryList as $item)
					            	{
					            		$var = JHTML::_('select.option', $item->id, $item->name );
					            		array_push($dealCategoryJOptList, $var);
					            	}
					            }
								if($isEdit){
									echo JHTML::_('select.genericList',$dealCategoryJOptList, 'pdt_cat_id[]', 'class="inputbox" size="10"  multiple="multiple"', 'value','text',null );
								} else {
					        	  echo JHTML::_('select.genericList',$dealCategoryJOptList, 'pdt_cat_id[]', 'class="inputbox" size="10" onDblClick="moveOptions(document.adminForm[\'pdt_cat_id[]\'],document.adminForm.pdt_cat_list)" multiple="multiple"', 'value','text',null );
								}
					        ?>
					     </div>
					</div>
			 </div>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="location_id"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_LOCATION'), JTEXT::_('TOOLTIP_DEAL_LOCATION_TITLE'), '', JTEXT::_('DEAL_LOCATION')); ?>
		 * </label></td>
		<td>
			<div>
					<div style="float: left; ">
						<div><b><?php echo JText::_('AVAILABLE_LOCATION');?></b></div>
						<div>
						<?php
						// create list location for combobox
						$locationJOptList = array();
						
							if(!empty($this->dealLocationList))
							{
								$locationList = $this->locationList;
								$dealLocationList = $this->dealLocationList;
								for ($i=0 ; $i < count($locationList); $i++)
								{
									$locationAvailable = false;
									for($x = 0 ; $x < count($dealLocationList); $x++)
									{
										if($locationList[$i]->id == $dealLocationList[$x]->id)
										{
											$locationAvailable = true;
										}
									}
									if(!$locationAvailable)
									{
										$var = JHTML::_('select.option', $locationList[$i]->id, $locationList[$i]->name );
										array_push($locationJOptList, $var);
									}
								}
							}
							else
							{
								foreach ($this->locationList as $item)
								{
									$var = JHTML::_('select.option', $item->id, $item->name );
									array_push($locationJOptList, $var);
								}
							}
							
						echo JHTML::_('select.genericList',$locationJOptList, 'location_list', 'class="inputbox" size="10" onDblClick="moveOptions(document.adminForm.location_list, document.adminForm[\'location_id[]\'])" multiple="multiple"' , 'value','text',null);
						?>
						</div>
					</div>
					<div style="float: left; padding: 40px 10px 0px 10px">
					
							<input style="width: 50px" type="button" name="Button" value="&gt;" onClick="moveOptions(document.adminForm.location_list, document.adminForm['location_id[]'])" />
	                            <br /><br />
	                        <input style="width: 50px"  <?php echo $isDisabled; ?> type="button" name="Button" value="&lt;" onClick="moveOptions(document.adminForm['location_id[]'],document.adminForm.location_list)" />
	                            <br /><br />
	                            
					</div>
					<div style="float: left; ">
						<div><b><?php echo JText::_('CURRENT_LOCATION');?></b></div>
						<div>
							<?php 
							$dealLocationJOptList = array();
							foreach($this->dealLocationList as $item)
							{
								$var = JHTML::_('select.option', $item->id, $item->name );
								array_push($dealLocationJOptList, $var);
							}
							
							if($isEdit){
								echo JHTML::_('select.genericList',$dealLocationJOptList, 'location_id[]', 'class="inputbox" size="10"  multiple="multiple"' , 'value','text',null );
							} else {
								echo JHTML::_('select.genericList',$dealLocationJOptList, 'location_id[]', 'class="inputbox" size="10" onDblClick="moveOptions(document.adminForm[\'location_id[]\'],document.adminForm.location_list)" multiple="multiple"' , 'value','text',null );
							}
							?>
				   		</div>
				    </div>
			</div>
		</td>
	</tr>
	<!--	
	<tr>
		<td width="100" align="right" class="key"><label for="status"> <?php echo JText::_( 'Status' ); ?>:
		</label></td>
		<td>			
			<?php
				$statusJOptList = array();
				array_push($statusJOptList, $emptyJOpt);
				foreach ($this->statusList as $key=>$name)
				{
					$var = JHTML::_('select.option', $key, JText::_($name) );
					array_push($statusJOptList, $var);
				}
				 
				echo JHTML::_('select.genericList',$statusJOptList, 'status', null , 'value','text',$row->status );
			?>
		</td>
	</tr>
 -->
	<tr>
		<td width="100" align="right" class="key"><label for="salesPerson"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_SALE_PERSON'),JTEXT::_('TOOLTIP_DEAL_SALE_PERSON_TITLE'), '', JTEXT::_('DEAL_SALE_PERSON')); ?>
		</label></td>
		<td><?php
		$salesPersonJOptList = array();
		array_push($salesPersonJOptList, $emptyJOpt);
		foreach ($this->salesPersonList as $item)
		{
			$var = JHTML::_('select.option', $item->id, $item->name );
			array_push($salesPersonJOptList, $var);
		}
			
		echo JHTML::_('select.genericList',$salesPersonJOptList, 'sales_person_id', null , 'value','text',$row->sales_person_id );
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="highlight"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_HIGHTLIGHT'),JTEXT::_('TOOLTIP_DEAL_HIGHTLIGHT_TITLE'), '', JTEXT::_('DEAL_HIGHLIHGTS')); ?>
		</label></td>
		<td><?php 
		$editor =& JFactory::getEditor();
		echo $editor->display('highlight', $row->highlight, '400', '200', '50', '3');
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="terms"> <?php echo JHTML::tooltip(JTEXT::_('TOOLTIP_DEAL_CONDITION'),JTEXT::_('TOOLTIP_DEAL_CONDITION_TITLE'), '', JTEXT::_('DEAL_TERMS_CONDITIONS')); ?>
		</label></td>
		<td><?php 
		$editor =& JFactory::getEditor();
		echo $editor->display('terms', $row->terms, '400', '200', '50', '3');
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="published"><?php echo JText::_( 'PUBLISHED' ); ?>:</label>
		</td>
		<td><?php 
		if ($row->published == null)
		echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', 1);
		else
		echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $row->published);
		?></td>
	</tr>
	<!-- Amol Start-->
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('Is Side Deal?');?></td>
		<td><?php 
			if ($row->is_side_deal == 0)
			echo JHTML::_('select.booleanlist', 'is_side_deal', 'class="inputbox"', 0);
			else
			echo JHTML::_('select.booleanlist', 'is_side_deal', 'class="inputbox"', $row->is_side_deal);
		?></td>
	</tr>
	<!-- Amol End-->
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
</div>

<input type="hidden" name="position"
	value="<?php echo $row->position; ?>" /> <input type="hidden" name="id"
	value="<?php echo $row->id; ?>" /> <input type="hidden" name="option"
	value="<?php echo $option;?>" /> <input type="hidden" name="controller"
	value="deal" /> <input type="hidden" name="task" value="" />
</div>
</form>
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

$rows = $this->dealList;
$option = 'com_enmasse';


function getUpIcon()
{
    //return "<img src='/administrator/components/com_enmasse/images/uparrow.png' />";
    $imgSrc = JURI::root()."/administrator/components/com_enmasse/images/";
    return "<img src='".$imgSrc."uparrow.png' />";

}
function getDownIcon()
{
    //return "<img src='/administrator/components/com_enmasse/images/downarrow.png' />";
    $imgSrc = JURI::root()."/administrator/components/com_enmasse/images/";
    return "<img src='".$imgSrc."downarrow.png' />";
}

$filter = $this->filter;
$emptyJOpt = JHTML::_('select.option', '', JText::_('') );

// create list location for combobox
$locationJOptList = array();
array_push($locationJOptList, $emptyJOpt);
foreach ($this->locationList as $item)
{
    $var = JHTML::_('select.option', $item->id, JText::_($item->name) );
    array_push($locationJOptList, $var);
}

$publishedJOptList = array();
array_push($publishedJOptList, JHTML::_('select.option', null, JText::_('') ));
array_push($publishedJOptList, JHTML::_('select.option', 1, JText::_('PUBLISHED') ));
array_push($publishedJOptList, JHTML::_('select.option', 0, JText::_('NOT_PUBLISHED') ));


// create list category for combobox
$categoryJOptList = array();
array_push($categoryJOptList, $emptyJOpt);
foreach ($this->categoryList as $item)
{
    $var = JHTML::_('select.option', $item->id, JText::_($item->name) );
    array_push($categoryJOptList, $var);
}

// create list status for combobox
$statusJOptList = array();
array_push($statusJOptList, $emptyJOpt);
foreach ($this->statusList as $key=>$name)
{
    $var = JHTML::_('select.option', $key, JText::_('DEAL_'.strtoupper(str_replace(' ','_',$name))) );
    array_push($statusJOptList, $var);
}
?>
<head>

<script type="text/javascript">
function setTask(id,position)
{
      var pos = "updatePosition"+position;
      var link = 'index.php?option=com_enmasse&controller=deal&task=updatePosition&id='+id+'&updatePosition='+document.adminForm.elements[pos].value;
	  location.href=link;
}

//Amol

function submitbutton(pressbutton) {

		if(pressbutton == 'voidDeal'){
			if(document.adminForm.boxchecked.value==0) {
							alert("Please make a selection from the List?");
							return false;
			}
			 var confirmmation = confirm("Do you Really want to Void the Deal?");
				 if (confirmmation == true)
				 {
					  $$("a").setStyle("opacity",0);
					  $(document.body).setStyle("opacity",0.5);
					  submitform(pressbutton);
				 }

		 }
		else if(pressbutton == 'confirmDeal'){
			if(document.adminForm.boxchecked.value==0) {
							alert("Please choose a Deal to confirm !");
							return false;
			}
			 var confirmmation = confirm("Do you Really want to confirm the Deal?");
				 if (confirmmation == true)
				 {
					 $$("a").setStyle("opacity",0);
					  $(document.body).setStyle("opacity",0.5);
					  submitform(pressbutton);
				 }

		 }
		else if(pressbutton == 'approveDeal'){
			if(document.adminForm.boxchecked.value==0) {
							alert("Please choose a Pending Deal to approve !");
							return false;
			}
			 var confirmmation = confirm("Do you Really want to approve the Deal?");
				 if (confirmmation == true)
				 {
					 $$("a").setStyle("opacity",0);
					  $(document.body).setStyle("opacity",0.5);
					  submitform(pressbutton);
				 }

		 }
		 else {
			submitform(pressbutton);
		}

	return false;
}

</script>
</head>
<body>
<form action="index.php">
<table>
	<tr>
		<td><b><?php echo JText::_('DEAL_NAME');?>: </b> <input type="text"
			name="filter[name]" value="<?php echo $filter['name']; ?>" /> <b><?php echo JText::_('DEAL_LOCATION');?>:
		</b> <?php echo JHTML::_('select.genericList', $locationJOptList, 'filter[location_id]', null , 'value', 'text', $filter['location_id']);?>
		<b><?php echo JText::_('DEAL_CATEGORY');?>: </b> <?php echo JHTML::_('select.genericList', $categoryJOptList, 'filter[category_id]', null , 'value', 'text', $filter['category_id']);?>
		<b><?php echo JText::_('PUBLISHED');?>: </b> <?php echo JHTML::_('select.genericList', $publishedJOptList, 'filter[published]', null , 'value', 'text', $filter['published']);?>
		<b><?php echo JText::_('DEAL_STATUS');?>: </b> <?php echo JHTML::_('select.genericList', $statusJOptList, 'filter[status]', null , 'value', 'text', $filter['status']);?>

		<input type="submit" value="<?php echo JText::_('DEAL_SEARCH');?>" />
		<input type="button" value="<?php echo JText::_('DEAL_RESET');?>"
			onClick="location.href='index.php?option=com_enmasse&controller=deal&filter[name]=&filter[location_id]=&filter[category_id]=&filter[published]=&filter[status]='" />
		</td>
	</tr>
</table>
<input type="hidden" name="option" value="com_enmasse" /> <input
	type="hidden" name="controller" value="deal" /></form>

<form action="index.php" method="post" name="adminForm"><br />

<div id="editcell"><?php echo JText::_('DEAL_NOTE');?>

<table class="adminlist" width="100%">
	<thead>
		<tr>
			<th width="5">#</th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th width="10"><?php echo JText::_( 'DEAL_ID' ); ?></th>
			<th width="110"><?php echo JText::_( 'DEAL_NAME' ); ?></th>
			<th width="225"><?php echo JText::_( 'DEAL_SHORT_DESC' ); ?></th>
			<th width="5"><?php echo JText::_( 'DEAL_MIN_QUANTITY' ); ?></th>
			<th width="5"><?php  echo JHTML::_( 'grid.sort', JText::_('DEAL_TOTAL_SOLD'), 'cur_sold_qty', $this->order['order_dir'], $this->order['order']); ?></th>
			<th width="5"><?php echo JText::_( 'PUBLISHED' ); ?></th>
			<th width="100"><?php echo JText::_( 'DEAL_END_AT' ); ?></th>
			<th width="90"><?php  echo JHTML::_( 'grid.sort', JText::_('DEAL_ORDER'), 'position', $this->order['order_dir'], $this->order['order']); ?></th>
			<th width="75"><?php  echo JHTML::_( 'grid.sort', JText::_('DEAL_STATUS'), 'status', $this->order['order_dir'], $this->order['order']); ?></th>
			<th width="100"><?php  echo JText::_('DEAL_CATEGORY'); ?></th>
			<th width="100"><?php echo JText::_( 'DEAL_LOCATION' ); ?></th>
			<th width="100"><?php echo JText::_( 'DEAL_SALE_PERSON' ); ?></th>
			<th width="80"><?php echo JText::_( 'CREATED_AT' ); ?></th>
			<th width="80"><?php echo JText::_( 'UPDATED_AT' ); ?></th>
			<th width="15"><?php echo JText::_( 'Is Side Deal?' ); ?></th>
		</tr>
	</thead>
	<?php
	for ($i=0; $i < $n=count( $rows ); $i++)
	{
	    $k = $i % 2;

	    $row = &$rows[$i];
	    $checked = JHTML::_('grid.id', $i, $row->id );
	    $published = JHTML::_('grid.published', $row, $i );
	    $link =  JRoute::_('index.php?option=' .
	    $option .'&controller=deal'.'&task=edit&cid[]='. $row->id) ;
	    ?>



	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $i+1; ?></td>
		<td><?php echo $checked; ?></td>
		<td><?php echo $row->id; ?></td>
		<td><a href="<?php echo $link; ?>"><?php echo $row->name; ?></a></td>
		<td><?php echo $row->short_desc; ?></td>
		<td><?php echo $row->min_needed_qty; ?></td>
		<td><?php echo $row->cur_sold_qty; ?></td>
		<td align="center"><?php echo $published; ?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->end_at); ?></td>
		<td><?php if ( $row->position > 1 ):?> <a href="#"
			onClick="location.href='index.php?option=com_enmasse&controller=deal&task=upPosition&id=<?php echo $row->id?>'" /><?php echo getUpIcon();?>
		</a> <?php endif;?> <?php if ( $row->position < count($rows) ):?> <a
			href="#"
			onClick="location.href='index.php?option=com_enmasse&controller=deal&task=downPosition&id=<?php echo $row->id?>'" /><?php echo getDownIcon();?>
		</a> <?php endif;?>
		<div style="float: right"><input type="text" size='5'
			value="<?php echo $row->position;?>"
			name='updatePosition<?php echo $i;?>'
			onchange='setTask(<?php echo $row->id; ?>,<?php echo $i; ?>)' /></div>
		</td>
		<td><?php
		echo JTEXT::_('DEAL_'.strtoupper(str_replace(' ','_',$row->status))); ?></td>
		<td align="center"><?php
		if($row->category_name != null)
		{
		    foreach ($row->category_name as $item)

		    echo '- '.$item->name.'<br />';
		    ;
		}
		?></td>
		<td align="center"><?php
		if($row->location_name != null)
		{
		    foreach ($row->location_name as $item)
		    echo '- '.$item->name.'<br />';
		}
		?></td>
		<td align="center"><?php  echo $row->sales_person_name;?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->created_at); ?></td>
		<td><?php echo DatetimeWrapper::getDisplayDatetime($row->updated_at); ?></td>
		<!-- Amol Start-->
		<td><?php $isSide = ($row->is_side_deal)?"Yes":"No"; echo $isSide;?></td>
		<!-- Amol End-->
	</tr>
	<?php
	}
	?>
	<tfoot>
		<tr>
			<td colspan="16"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
</table>
</div>
<input type="hidden" name="option" value="<?php echo $option;?>" /> <input
	type="hidden" name="controller" value="deal" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="boxchecked"
	value="0" /> <input type="hidden" name="filter_order"
	value="<?php echo $this->order['order']; ?>" /> <input type="hidden"
	name="filter_order_Dir"
	value="<?php echo $this->order['order_dir']; ?>" /> <input
	type="hidden" name="filter[name]"
	value="<?php echo $filter['name']; ?>" /> <input type="hidden"
	name="filter[location_id]"
	value="<?php echo $filter['location_id']; ?>" /> <input type="hidden"
	name="filter[category_id]"
	value="<?php echo $filter['category_id']; ?>" /> <input type="hidden"
	name="filter[published]" value="<?php echo $filter['published']; ?>" />
<input type="hidden" name="filter[status]"
	value="<?php echo $filter['status']; ?>" /></form>
<div id="log_res1" class="notific"></div>
</body>

<?php
defined( '_JEXEC' ) or die();
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
$logged_user = &JFactory::getUser();
$jApp = &JFactory::getApplication();
if (!$jApp->isAdmin() && $this->toolbar) {
    echo $this->toolbar->render();
    echo '<div style="clear:both;"></div>';
}
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<fieldset class="filter">
<table>
	<tr>
		<td><label for="search"><?php echo JText::_( 'ADMIN_POSTCODE_LABEL_SEARCH' ); ?>:</label> <input
			type="text" name="search" id="search"
			value="<?php echo $this->state->search; ?>" size="60"
			title="<?php echo $this->escape(JText::_( 'ADMIN_POSTCODE_TOOLTIP_SEARCH' )); ?>" />
		<button type="submit"><?php echo JText::_( 'ADMIN_POSTCODE_LABEL_GO' ); ?></button>
		<button type="button"
			onclick="document.getElementById('search').value='';this.form.submit();">
			<?php echo JText::_( 'ADMIN_POSTCODE_LABEL_CLEAR' ); ?></button>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
<div class="clr"></div>
</fieldset>

<table class="adminlist">
	<thead>
		<tr>
			<th width="20"></th>
			<th width="30" align="center">
			<?php echo JHTML::_( 'grid.sort', 'ADMIN_POSTCODE_LABEL_ID', 's.id',
			             $this->state->orderDirn, $this->state->orderCol ); ?>
			</th>
			<th class="left">
			<?php echo JHTML::_( 'grid.sort', 'ADMIN_POSTCODE_LABEL_NEIGHBOURHOOD', 's.name',
			             $this->state->orderDirn, $this->state->orderCol ); ?>
			</th>
			<th class="left">
			<?php echo JHTML::_( 'grid.sort', 'ADMIN_POSTCODE_LABEL_POSTCODE', 's.postcode',
			             $this->state->orderDirn, $this->state->orderCol ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
<?php
$k = 0;
$i = 0;
if (count($this->items)>0) {
    foreach ($this->items as $item) { ?>
    		<tr class="row<?php echo $k; ?>">
    			<td style="text-align: center">
        <?php
        $link = JRoute::_( 'index.php?option=com_hub2'
        .'&view=postcode&model=postcode&task=edit&cid[]='.$item->id );
        $canEdit = ($item->checked_out<=0 ||
                ($item->checked_out>0 && $item->checked_out==$logged_user->id));

        $item->editor = &JFactory::getUser($item->checked_out)->name;
        $checked = JHTML::_( 'grid.checkedout', $item, $i);
        echo $checked;
        ?>
                </td>
    			<td><?php echo $item->id; ?></td>
    			<td align="center">
        <?php
    	if ($canEdit) {
            ?>
            <a href="<?php echo $link;?>">
    				<?php echo $item->name; ?>
    		</a>
            <?php
        } else {
            echo $item->name;
        }
        ?>
    			</td>
    			<td align="center">
    			<?php if ($canEdit) { ?>
    			    <a href="<?php echo $link;?>">
    				    <?php echo $item->postcode; ?>
    			    </a>
    			<?php } else {
    			         echo $item->postcode;
    			} ?>
    			</td>
    		</tr>
        <?php
        $k = 1 - $k;
        $i++;
    }
} else {
    ?>
        <tr>
            <td colspan="4" align="center">
					No postcode found
            </td>
        </tr>
    <?php
}
?>
	</tbody>
</table>
<input type="hidden" name="option" value="com_hub2" /> <input
	type="hidden" name="task" value="" /> <input type="hidden" name="view"
	value="postcode" /> <input type="hidden" name="model" value="postcode" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="filter_order" value="<?php echo $this->state->orderCol; ?>" /> <input
	type="hidden" name="filter_order_Dir"
	value="<?php echo $this->state->orderDirn; ?>" /> <input type="hidden"
	name="<?php echo JUtility::getToken();?>" value="1" /></form>

<script language="javascript">
 function submitbutton(action){
    if(action=='trash'){
        if (confirm('Are you Sure want to delete this postcode?')){
        document.adminForm.task.value=action;
        document.adminForm.submit();
        }
    }else{
        document.adminForm.task.value=action;
        document.adminForm.submit();
    }
}
</script>

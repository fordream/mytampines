<?php
defined( '_JEXEC' ) or die();
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
$logged_user = &JFactory::getUser();
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<fieldset class="filter">
<table>
	<tr>
		<td><label for="search"><?php echo JText::_( 'ADMIN_EDITOR_LABEL_SEARCH' ); ?>:</label> <input
			type="text" name="search" id="search"
			value="<?php echo $this->state->search; ?>" size="60"
			title="<?php echo $this->escape(JText::_( 'ADMIN_EDITOR_TOOLTIP_SEARCH' )); ?>" />
		<button type="submit"><?php echo JText::_( 'ADMIN_EDITOR_LABEL_GO' ); ?></button>
		<button type="button"
			onclick="document.getElementById('search').value='';this.form.submit();">
			<?php echo JText::_( 'ADMIN_EDITOR_LABEL_CLEAR' ); ?></button>
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
			<th width="5%" align="center">
            <?php echo JHTML::_( 'grid.sort', 'ADMIN_EDITOR_LABEL_ID', 's.id',
                         $this->state->orderDirn, $this->state->orderCol ); ?>
			</th>
            <th class="left" width="25%">
            <?php echo JHTML::_( 'grid.sort', 'ADMIN_EDITOR_LABEL_USERNAME', 's.username',
                         $this->state->orderDirn, $this->state->orderCol ); ?>
            </th>
			<th class="left" width="35%">
			<?php echo JHTML::_( 'grid.sort', 'ADMIN_EDITOR_LABEL_NAME', 's.name',
			             $this->state->orderDirn, $this->state->orderCol ); ?>
			</th>
			<th width="35%">
			<?php echo JHTML::_( 'grid.sort', 'ADMIN_EDITOR_LABEL_EMAIL', 's.email',
			             $this->state->orderDirn, $this->state->orderCol ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
<?php
$k = 0;
$i =0;
if (count($this->items)>0) {
    foreach ($this->items as $item) { ?>
		<tr class="row<?php echo $k; ?>">
			<td style="text-align: center">
        <?php
        $link = JRoute::_( 'index.php?option=com_hub2&view=editorsitesrelations&task=edit&cid[]='.$item->id );
        // TODO this is a hack! check_out does not exist for edito sites relations
        $item->checked_out = 0;
        $canEdit = ($item->checked_out<=0 ||
            ($item->checked_out>0 && $item->checked_out==$logged_user->id));
        //$item->editor = &JFactory::getUser($item->checked_out)->name;
        $checked = JHTML::_( 'grid.checkedout', $item, $i);
        echo $checked;
        ?>
            </td>
			<td><?php echo $item->id; ?></td>
            <td align="left">
        <?php if ($canEdit) { ?>
                <a href="<?php echo $link;?>"><?php echo $item->username; ?></a>
        <?php
        } else {
            echo $item->username;
        }
        ?>
            </td>
			<td align="left">
        <?php if ($canEdit) { ?>
			    <a href="<?php echo $link;?>"><?php echo $item->name; ?></a>
        <?php
        } else {
            echo $item->name;
        }
        ?>
			</td>
			<td><?php echo $item->email; ?></td>
		</tr>
    <?php
        $k = 1 - $k;
        $i++;
    }
} else {
    ?>
        <tr>
            <td colspan="6" align="center">
				No editors found
            </td>
        </tr>
    <?php
}
?>
	</tbody>
</table>
<input type="hidden" name="option" value="com_hub2" /> <input
	type="hidden" name="task" value="" /> <input type="hidden" name="view"
	value="editorsitesrelations" /> <input type="hidden" name="model" value="editorsitesrelations" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="filter_order" value="<?php echo $this->state->orderCol; ?>" /> <input
	type="hidden" name="filter_order_Dir"
	value="<?php echo $this->state->orderDirn; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>

<script language="javascript">
 function submitbutton(action){
    if(action=='trash'){
        if (confirm('Are you Sure want to delete this Editor\'s site relations?')){
        document.adminForm.task.value=action;
        document.adminForm.submit();
        }
    }else{
        document.adminForm.task.value=action;
        document.adminForm.submit();
    }
}
</script>

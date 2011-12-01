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
		<td><label for="search"><?php echo JText::_( 'ADMIN_SITE_LABEL_SEARCH' ); ?>:</label> <input
			type="text" name="search" id="search"
			value="<?php echo $this->state->search; ?>" size="60"
			title="<?php echo $this->escape(JText::_( 'ADMIN_SITE_TOOLTIP_SEARCH' )); ?>" />
		<button type="submit"><?php echo JText::_( 'ADMIN_SITE_LABEL_GO' ); ?></button>
		<button type="button"
			onclick="document.getElementById('search').value='';this.form.submit();">
			<?php echo JText::_( 'ADMIN_SITE_LABEL_CLEAR' ); ?></button>
		</td>
		<td><label for="template"> <?php echo JText::_( 'ADMIN_SITE_LABEL_IS_TEMPLATE' ); ?> </label>
		<select name="is_template" id="is_template" class="inputbox"
			onchange="this.form.submit()">
<?php
if (!isset($this->state->is_template)) {
    echo JHTML::_( 'select.options', $this->is_template,
                         'value', 'text', '-1' );
} else {
    echo JHTML::_( 'select.options', $this->is_template,
                         'value', 'text', $this->state->is_template );
}
?>
		</select></td>
	</tr>
</table>
<div class="clr"></div>
</fieldset>

<table class="adminlist">
	<thead>
		<tr>
			<th width="20"></th>
			<th width="100" align="center">
			<?php echo JHTML::_( 'grid.sort', 'ADMIN_SITE_LABEL_ID', 's.id',
			             $this->state->orderDirn, $this->state->orderCol ); ?>
			</th>
			<th class="left" width="200">
			<?php echo JHTML::_( 'grid.sort', 'ADMIN_SITE_LABEL_NAME', 's.name',
			             $this->state->orderDirn, $this->state->orderCol ); ?>
			</th>
            <th width="100">
            <?php echo JHTML::_( 'grid.sort', 'ADMIN_SITE_LABEL_LANGUAGE', 's.lang',
                         $this->state->orderDirn, $this->state->orderCol ); ?>
            </th>
			<th width="80">
			<?php echo JHTML::_( 'grid.sort', 'ADMIN_SITE_LABEL_TEMPLATE', 's.is_template',
			             $this->state->orderDirn, $this->state->orderCol ); ?>
			</th>
			<th width="100">
			<?php echo JHTML::_( 'grid.sort', 'ADMIN_SITE_LABEL_URL', 's.url',
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
$i = 0;
foreach ($this->items as $item) { ?>
		<tr class="row<?php echo $k; ?>">
			<td style="text-align: center">
    <?php
        $link = JRoute::_( 'index.php?option=com_hub2&view=site&model=site&task=edit&cid[]='.$item->id );
        $canEdit = ($item->checked_out<=0 ||
            ($item->checked_out>0 && $item->checked_out==$logged_user->id));
        $item->editor = &JFactory::getUser($item->checked_out)->name;
        $checked = JHTML::_( 'grid.checkedout', $item, $i);
        echo $checked;
        ?>
            </td>
			<td><?php echo $item->id; ?></td>
			<td align="left">
    <?php if ($canEdit) { ?>
			    <a href="<?php echo $link;?>"><?php echo $item->name; ?></a>
        <?php
    } else {
        echo $item->name;
    }
    ?>
			</td>
            <td align="left"><?php echo $item->lang; ?></td>
			<td align="left">
    <?php
    if ($item->is_template == '0') {
        echo JText::_("No");
    } else {
        echo JText::_("Yes");
    }
    ?>
            </td>
			<td align="left">
			<a target="_blank" href="<?php echo $item->url;?>">
            <?php echo $item->url;?>
			</a>
			</td>
		</tr>
    <?php
    $k = 1 - $k;
    $i++;
}
?>
	</tbody>
</table>
<input type="hidden" name="option" value="com_hub2" /> <input
	type="hidden" name="task" value="" /> <input type="hidden" name="view"
	value="site" /> <input type="hidden" name="model" value="site" /> <input
	type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="filter_order" value="<?php echo $this->state->orderCol; ?>" /> <input
	type="hidden" name="filter_order_Dir"
	value="<?php echo $this->state->orderDirn; ?>" /> <input type="hidden"
	name="<?php echo JUtility::getToken();?>" value="1" /></form>

<script language="javascript">
 function submitbutton(action){
    if(action=='trash'){
        if (confirm('Are you sure you want to delete this site?')){
        document.adminForm.task.value=action;
        document.adminForm.submit();
        }
    }else{
        document.adminForm.task.value=action;
        document.adminForm.submit();
    }
}
</script>

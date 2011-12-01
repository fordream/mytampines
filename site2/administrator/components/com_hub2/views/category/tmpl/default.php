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
            <td>
                <label for="search"><?php echo JText::_( 'ADMIN_CATEGORY_LABEL_SEARCH' ); ?>:</label>
                <input type="text" name="search" id="search"
                value="<?php echo $this->state->search; ?>" size="60"
                title="<?php echo $this->escape(JText::_( 'ADMIN_CATEGORY_TOOLTIP_SEARCH' )); ?>" />
                <button type="submit"><?php echo JText::_( 'ADMIN_CATEGORY_LABEL_GO' ); ?></button>
                <button type="button"
                    onclick="document.getElementById('search').value='';this.form.submit();">
                    <?php echo JText::_( 'ADMIN_CATEGORY_LABEL_CLEAR' ); ?>
                </button>
            </td>
            <td>
                <label for="level">
                    <?php echo JText::_( 'ADMIN_CATEGORY_LABEL_LEVELS' ); ?>
                </label>
                <select name="level" id="level" class="inputbox" onchange="this.form.submit()">
                <?php echo JHTML::_( 'select.options', $this->levelopt,
                         'value', 'text', $this->state->level ); ?>
                </select>
            </td>
        </tr>
        </table>
        <div class="clr"></div>
    </fieldset>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20"></th>
				<th width="30">
					<?php echo JText::_( 'ADMIN_CATEGORY_LABEL_ID' ); ?>
				</th>
				<th class="left">
					<?php echo JText::_( 'ADMIN_CATEGORY_LABEL_TITLE' ); ?>
				</th>
				<th class="left" width="10%">
					<?php echo JText::_( 'ADMIN_CATEGORY_LABEL_ORDER' );  ?>
					<?php // echo JHTML::_('grid.order',  $this->items ); // no saving order ?>
				</th>
                <th class="left">
                    <?php echo JText::_( 'ADMIN_CATEGORY_LABEL_SITES' ); ?>
                </th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
            </tr>
        </tfoot>
        <tbody>
<?php
$k = 0;
$n = count($this->items);
$page = new JPagination($n,0,0);
if ($n>0) {
    $pre_id=array();
    for ($i=0; $i < $n; $i++) {
        $item = &$this->items[$i];

        $link = JRoute::_("index.php?option=com_hub2&view=category&model=category&task=edit&cid[]=".$item->id );
        $canEdit = ($item->checked_out<=0 ||
            ($item->checked_out>0 && $item->checked_out==$logged_user->id));
        $item->editor = &JFactory::getUser($item->checked_out)->name;
        $checked    = JHTML::_('grid.checkedout',   $item, $i );
        ?>
        <tr class="row<?php echo $k; ?>">
            <td style="text-align:center">
        <?php
            echo $checked;
        ?>
            </td>
            <td style="text-align:center">
			     <?php echo $item->id; ?>
            </td>
            <td align="left">
        <?php
        $level = (int)$item->level;
        if (!$level) $level = 1;
        $name = str_repeat(' - ',$level-1).$item->title;
        if ($canEdit) { ?>
                <a href="<?php echo $link;?>">
                    <?php echo $name; ?>
                </a>
        <?php
        } else {
            echo $name;
        }
        ?>
            </td>
            <td class="order">
                <span><?php echo $page->orderUpIcon( $i, ($item->level == @$this->items[$i-1]->level), 'orderup', 'Move Up', true ); ?></span>
                <span><?php echo $page->orderDownIcon( $i, $n, ($item->level == @$this->items[$i+1]->level), 'orderdown', 'Move Down', true ); ?></span>
                <?php echo $item->ordering; ?>
                <!--  do not allow ordering to be input //-->
                <!-- <input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="text_area" style="text-align: center" /> //-->
            </td>
            <td style="text-align:center">
            <?php echo $item->sites;?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
} else {
    ?>
        <tr>
            <td colspan="5" align="center">
					No category found
            </td>
        </tr>
    <?php
}
?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_hub2" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="category" />
	<input type="hidden" name="model" value="category" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="<?php echo JUtility::getToken();?>" value="1" />
</form>
<script language="javascript">
 function submitbutton(action){
    if(action=='trash'){
        if (confirm('Are you sure you want to delete this category?')){
        document.adminForm.task.value=action;
        document.adminForm.submit();
        }
    }else{
        document.adminForm.task.value=action;
        document.adminForm.submit();
    }
}
</script>

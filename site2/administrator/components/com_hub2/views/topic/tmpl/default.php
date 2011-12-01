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
            <td>
                <label for="search"><?php echo JText::_( 'ADMIN_TOPIC_LABEL_SEARCH' ); ?>:</label>
                <input type="text" name="search" id="search"
                value="<?php echo $this->state->search; ?>" size="60"
                title="<?php echo $this->escape(JText::_( 'ADMIN_TOPIC_TOOLTIP_SEARCH' )); ?>" />
                <button type="submit"><?php echo JText::_( 'ADMIN_TOPIC_LABEL_GO' ); ?></button>
                <button type="button"
                    onclick="document.getElementById('search').value='';this.form.submit();">
                    <?php echo JText::_( 'ADMIN_TOPIC_LABEL_CLEAR' ); ?>
                </button>
            </td>
            <td>
                <label for="level">
                    <?php echo JText::_( 'ADMIN_TOPIC_LABEL_LEVELS' ); ?>
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
	<table class="adminlist" width="100%">
		<thead>
			<tr>
				<th width="20">&nbsp;</th>
				<th width="30">
					<?php echo JText::_( 'ADMIN_TOPIC_LABEL_ID'); ?>
				</th>
				<th class="left">
					<?php echo JText::_( 'ADMIN_TOPIC_LABEL_Topic'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
            </tr>
        </tfoot>
        <tbody>
<?php
$k = 0;
$i=0;
if (count($this->items)>0) {
    $pre_id=array();
    foreach ($this->items as $item) { ?>
        <tr class="row<?php echo $k; ?>">
            <td style="text-align:center">
        <?php
        $link = JRoute::_("index.php?option=com_hub2&view=topic&model=topic&task=edit&cid[]=".$item->id );
        $canEdit = ($item->checked_out<=0 ||
            ($item->checked_out>0 && $item->checked_out==$logged_user->id));
        $item->editor = &JFactory::getUser($item->checked_out)->name;
        $checked = JHTML::_( 'grid.checkedout', $item, $i);
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
        $name = str_repeat(' - ',$level-1).$item->name;
        if ($canEdit) { ?>
                <a href="<?php echo $link;?>"><?php echo $name; ?></a>
        <?php
        } else {
            echo $name;
        }
        ?>
            </td>
        </tr>
        <?php
        $i++;
        $k = 1 - $k;
    }
} else {
    ?>
        <tr>
            <td colspan="3" align="center">
					No topic found
            </td>
        </tr>
    <?php
}
?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_hub2" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="topic" />
	<input type="hidden" name="model" value="topic" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="<?php echo JUtility::getToken();?>" value="1" />
</form>
<script language="javascript">
 function submitbutton(action){
    if(action=='trash'){
        if (confirm('Are you sure you want to delete this topic?')){
        document.adminForm.task.value=action;
        document.adminForm.submit();
        }
    }else{
        document.adminForm.task.value=action;
        document.adminForm.submit();
    }
}
</script>

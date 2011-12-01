<?php

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
?>
<fieldset class="adminform">
<?php
if (count($this->errors)>0) {?>
    <dl id="system-message">
        <dt class="message">Message</dt>
        <dd class="message message fade">
        <ul>
    <?php
    foreach ($this->errors as $name=>$error) {
        ?>
            <li>
        <?php
        echo JText::_($error);
        ?>
            </li>
        <?php
    }
    ?>
        </ul>
        </dd>
    </dl>
    <?php
}
?>
<form action="" method="post" name="adminForm" class="form-validate" id="siteparamssetvalue-form">
<legend><?php echo JText::_('ADMIN_SITE_LABEL_SITEPARAMS').JText::sprintf( ' #%d - %s', $this->item->id, $this->item->name); ?></legend>
<table class="adminform">
	<tbody>
        <?php foreach ($this->sites as $site) { ?>
        <tr>
            <td width="150">&nbsp;</td>
            <td><?php echo $site->name; ?></td>
            <td><input type="text" size="52" class="inputbox validate required custom-validate-length_0-255 @lbl"
            value="<?php echo $this->values[$site->id]['value'];?>" id="value_<?php echo $site->id;?>" name="value[<?php echo $site->id;?>][value]">
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
	</tbody>
</table>
<input type="hidden" name="option" value="com_hub2" /> <input
	type="hidden" name="model" value="siteparams" /> <input type="hidden"
	name="view" value="siteparams" /> <input type="hidden" name="return"
	value="siteparams" /> <input type="hidden" name="task" value="" />
	<input type="hidden" name="check" value="post" /></fieldset>
    <input type="hidden" name="id" value="<?php echo $this->item->id;?>" />';
    <input type="hidden"
    name="<?php echo JUtility::getToken();?>" value="1" />
</form>
<?php
    // add jQuery
    $document = &JFactory::getDocument();
    // add custom validation js
    $document->addScript(JURI::root(true).'/components/com_hub2/js/form-validation.js');
    // add form submission function
    $document->addScript(JURI::root(true).'/administrator/components/com_hub2/js/jxform.js');
?>
<script language="javascript" type="text/javascript">
addCustomValidators();
</script>
<style>
.invalid {color:red;}
input.invalid{border:1px solid red;}
</style>
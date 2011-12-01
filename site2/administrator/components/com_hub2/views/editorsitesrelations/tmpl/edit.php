<?php

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
$fieldsMain		= $this->form->getFields('jxform');
$fieldsOther     = $this->profileform->getFields('jxform');
echo $this->form->getHead();
?>
<fieldset class="adminform">
<?php
if (count($this->errors)>0) {?>
    <dl id="system-message">
        <dt class="error">Message</dt>
        <dd class="error message fade">
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
<legend><?php echo JText::_('ADMIN_EDITOR_LABEL_EDITOR').JText::sprintf( ' #%d', $this->item->id); ?></legend>
<table class="admintable">
	<tbody>
		<tr>
			<td width="150">&nbsp;</td>
			<td class="key"><?php echo JText::_('ADMIN_EDITOR_LABEL_EDITOR_NAME');?></td>
			<td><?php echo $this->item->name; ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="key"><?php echo JText::_('ADMIN_EDITOR_LABEL_EDITOR_EMAIL');?></td>
			<td><?php echo $this->item->email; ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="key"><?php echo $fieldsMain['site']->label; ?></td>
			<td><?php echo $fieldsMain['site']->field; ?></td>
		</tr>
        <tr>
            <td>&nbsp;</td>
            <td class="key"><?php echo $fieldsOther['profile']->label; ?></td>
            <td>
<table class="paramlist admintable">
    <tbody>
    <?php
    foreach ($fieldsOther['profile']->field as $field) { ?>
        <tr>
            <td class="paramlist_key"><?php echo $field->label; ?></td>
            <td class="paramlist_value"><?php echo $field->field; ?></td>
        </tr>
        <?php
    } ?>
    </tbody>
</table>
            </td>
        </tr>
    </tbody>
</table>
<input type="hidden" name="option" value="com_hub2" /> <input
	type="hidden" name="model" value="editorsitesrelations" /> <input type="hidden"
	name="view" value="editorsitesrelations" /> <input type="hidden" name="return"
	value="editorsitesrelations" /> <input type="hidden" name="task" value="" /></fieldset>
<?php if (is_object( $this->item)  && $this->item->id > 0) {
    echo '<input type="hidden" name="jxform[user_id]" value="'.$this->item->id.'" />';
}
echo $this->form->getFoot(); ?>
<?php
    // add jQuery
    $document = &JFactory::getDocument();
    // add custom validation js
    $document->addScript(JURI::root(true).'/components/com_hub2/js/form-validation.js');
    // add form submission function
    $document->addScript(JURI::root(true).'/administrator/components/com_hub2/js/jxform.js');
?>
<script language="javascript" type="text/javascript">
function submitbutton(task) {
    submitform(task);
}
jQuery(function() {
    addCustomValidators();

});
</script>

<style>
.invalid {color:red;}
input.invalid{border:1px solid red;}
</style>
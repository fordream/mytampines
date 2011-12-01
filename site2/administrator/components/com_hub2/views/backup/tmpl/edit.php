<?php

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
$fieldsMain     = $this->form->getFields('jxform');
echo $this->form->getHead();
?>
<fieldset class="adminform">
<table class="admintable">
    <tbody>
    <?php foreach ($fieldsMain as $field) { ?>
        <tr>
            <td width="150">&nbsp;</td>
            <td class="key"><?php echo $field->label; ?></td>
            <td><?php echo $field->field; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<input type="hidden" name="option" value="com_hub2" />
<input type="hidden" name="view" value="backup" />
<input type="hidden" name="return" value="backup" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="check" value="" />
<input type="hidden"
    name="<?php echo JUtility::getToken();?>" value="1" />
<?php
echo $this->form->getFoot();
$document = &JFactory::getDocument();
?>

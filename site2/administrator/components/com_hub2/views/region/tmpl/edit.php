<?php

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
$fieldsMain		= $this->form->getFields('jxform');
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
<legend><?php echo JText::_('ADMIN_REGION_LABEL_REGION').JText::sprintf( ' #%d', $this->item->id); ?></legend>
<table class="admintable">
	<tbody>
            <tr>
                <td width="100">&nbsp;</td>
                <td class="key"><?php echo $fieldsMain['name']->label;?></td>
                <td><?php echo $fieldsMain['name']->field;?></td>
            </tr>
            <tr>
                <td width="100">&nbsp;</td>
                <td class="key"><?php echo $fieldsMain['parent_id']->label;?></td>
                <td><?php echo $fieldsMain['parent_id']->field;?></td>
            </tr>
            <tr>
                <td width="100">&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="option" value="com_hub2" />
    <input type="hidden" name="model" value="region" />
    <input type="hidden" name="view" value="region" />
    <input type="hidden" name="return" value="region" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="check" value="post" />
    <input type="hidden" name="id" value="<?php echo $this->item->id;?>" />
</fieldset>
<?php echo $this->form->getFoot();?>
<?php
    // add jQuery
    $document = &JFactory::getDocument();
    // add custom validation js
    $document->addScript(JURI::root(true).'/components/com_hub2/js/form-validation.js');
    // add form submission function
    $document->addScript(JURI::root(true).'/administrator/components/com_hub2/js/jxform.js');
?>
<script type="text/javascript">
jQuery(function() {
    addCustomValidators();

});
</script>
<style>
.invalid {color:red;}
input.invalid{border:1px solid red;}
</style>

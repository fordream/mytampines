<?php

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
$fieldsMain		= $this->form->getFields('jxform');
$jApp = &JFactory::getApplication();
if (!$jApp->isAdmin() && $this->toolbar) {
    echo $this->toolbar->render();
    echo '<div style="clear:both;"></div>';
}
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
<legend><?php echo JText::_('ADMIN_POSTCODE_LABEL_POSTCODE').JText::sprintf( ' #%d', $this->item->id); ?></legend>
<table class="admintable">
  <tbody>
  <?php foreach ($fieldsMain as $field) { ?>
    <tr>
      <td width="150">&nbsp;</td>
      <td class="key"><?php echo $field->label; ?></td>
      <td><?php echo $field->field; ?></td>
    </tr>
  <?php } ?>
      <tr valign="top">
            <td>&nbsp;</td>
            <td class="key"><?php echo JText::_('ADMIN_POSTCODE_LABEL_SITES');?></td>
            <td>
                <select name="jxform_site[]" id="jxform_site"
                style="width: 200px" size="10" multiple>
                <?php echo JHTML::_( 'select.options', $this->all_sites,
                                'value', 'text', $this->postcode_sites); ?>
                </select>
            </td>
        </tr>
  </tbody>
</table>
<input type="hidden" name="option" value="com_hub2" /> <input
  type="hidden" name="model" value="postcode" /> <input type="hidden"
  name="view" value="postcode" /> <input type="hidden" name="return"
  value="postcode" /> <input type="hidden" name="task" value="" />
  <input type="hidden" name="check" value="post" /></fieldset>
<?php if (is_object( $this->item)  && $this->item->id > 0) {
    echo '<input type="hidden" name="id" value="'.$this->item->id.'" />';
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
<script type="text/javascript">
jQuery(function() {
    addCustomValidators();

});
</script>
<style>
.invalid {color:red;}
input.invalid{border:1px solid red;}
</style>
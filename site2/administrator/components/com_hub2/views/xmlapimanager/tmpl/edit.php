<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
$fieldsMain		= $this->form->getFields('jxform');
JHTML::_('behavior.calendar');
?>
<script type="text/javascript">
jQuery(function() {
    addCustomValidators();
});

</script>
<?php
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
<legend><?php echo JText::_('ADMIN_SITE_LABEL_XMLAPIMANAGER').JText::sprintf( ' #%d', $this->item->id); ?></legend>
<table class="admintable">
	<tbody>
        <tr>
          <td width="150">&nbsp;</td>
          <td class="key"><?php echo $fieldsMain['metadata']->label; ?></td>
          <td><?php echo $fieldsMain['metadata']->field; ?></td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td class="key"><?php echo $fieldsMain['key_desc']->label; ?></td>
          <td><?php echo $fieldsMain['key_desc']->field; ?></td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td class="key"><label id="jxform_exp_date-lbl" for="jxform_exp_date"><?php echo JText::_( 'ADMIN_SITE_XMLAPI_EXP_DATE' ); ?></label></td>
          <td><input name="jxform[exp_date]" id="jxform_exp_date" value="<?php echo $this->item->exp_date;?>" class="inputbox required" type="text">
          <img class="calendar" src="templates/system/images/calendar.png" alt="calendar" id="jxform_exp_date_img"></td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td class="key"><?php echo $fieldsMain['user_id']->label; ?></td>
          <td><?php echo $fieldsMain['user_id']->field; ?></td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td class="key"><?php echo $fieldsMain['published']->label; ?></td>
          <td><?php echo $fieldsMain['published']->field; ?></td>
        </tr>
     </tbody>
</table>
<script type="text/javascript">
Calendar.setup({
    inputField  : "jxform_exp_date",         // ID of the input field
    ifFormat    : "%Y-%m-%d",    // the date format
	button      : "jxform_exp_date_img"       // ID of the button
});
</script>

<input type="hidden" name="option" value="com_hub2" />
<input type="hidden" name="model" value="xmlapimanager" />
<input type="hidden" name="view" value="xmlapimanager" />
<input type="hidden" name="return" value="xmlapimanager" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="check" value="post" />
</fieldset>
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
<style>
.invalid {color:red;}
input.invalid{border:1px solid red;}
</style>
<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
// get the content for the tabs
$fieldsMain     = $this->form->getFields('jxform');
$paramsGroup    = $this->form->getFields('jxform','params');
$fieldsParams   = $paramsGroup['params'];
echo $this->form->getHead();
?>
<fieldset class="adminform"><?php
if (count($this->errors)>0) {?>
<dl id="system-message">
  <dt class="error">Message</dt>
  <dd class="error message fade">
  <ul>
  <?php
  foreach ($this->errors as $name=>$error) {
      ?>
    <li><?php
    echo JText::_($error);
    ?></li>
    <?php
  }
  ?>
  </ul>
  </dd>
</dl>
  <?php
}
?> <legend><?php echo JText::_('ADMIN_CATEGORY_LEGEND') .  JText::sprintf( ' #%d', $this->item->id); ?></legend>
<table class="admintable">
  <tbody>
    <?php
  foreach ($fieldsMain as $field) {
      ?>
    <tr id="<?php echo $field->name;?>" <?php if ($field->name == 'content_types' && $this->existing_parent_id) {echo 'style="display:none"';} ?>>
      <td width="150">&nbsp;</td>
      <td class="key"><?php echo $field->label; ?></td>
      <td><?php echo $field->field; ?></td>
    </tr>
    <?php
  } ?>
        <tr valign="top">
            <td>&nbsp;</td>
            <td class="key"><?php echo JText::_('ADMIN_CATEGORY_LABEL_SITES');?></td>
            <td>
                <select name="jxform_site[]" id="jxform_site"
                style="width: 200px" size="10" multiple>
                <?php echo JHTML::_( 'select.options', $this->all_sites,
                                'value', 'text', $this->category_sites); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="key"><?php echo $fieldsParams->label?></td>
            <td>
<table class="paramlist admintable">
  <tbody>
  <?php
  foreach ($fieldsParams->field as $field) { ?>
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
        <tr>
            <td>&nbsp;</td>
            <td class="key"><?php echo JText::_('ADMIN_CATEGORY_LABEL_MEDIA'); ?></td>
            <td>
<table class="adminform content">
  <tbody>
  <?php
     echo $this->mediaHTML;
    ?>
  </tbody>
</table>
            </td>
        </tr>
    </tbody>
</table>

<div class="clr"></div>

<input type="hidden" name="option" value="com_hub2" /> <input
  type="hidden" name="model" value="category" /> <input type="hidden"
  name="view" value="category" /> <input type="hidden" name="return"
  value="category" /> <input type="hidden" name="task" value="" />
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
    // add form insert function
    $document->addScript(JURI::root(true).'/components/com_hub2/js/insertvalue.js');
    ?>
<script type="text/javascript">
jQuery(function() {
    addCustomValidators();
    addTitleOnBlurHandler('jxform[title]','jxform[alias]');
});
function submitbutton(task) {
    var form = document.adminForm;
    if (task == "cancel" || myValidate(form)) {
        <?php
            $editor =& JFactory::getEditor();
            echo $editor->save( 'jxform[body]' );
        ?>
        submitform(task);
    }
}

window.onDomReady(function() {
    // add on select function to the parent drop down
  jQuery("#jxform_parent_id").change(function() {
    val = jQuery(this).val();
    if (val) {
            // hide
            jQuery('#content_types').hide();
    } else {
            // display
            jQuery('#content_types').show();
    }
    });
});
</script>
<style>
.invalid {
  color: red;
}

input.invalid {
  border: 1px solid red;
}
</style>

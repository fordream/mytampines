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
<style type="text/css">
/*<![CDATA[*/
.paramtype {
  width: 90px;
}
/*]]>*/
</style>
<legend><?php echo JText::sprintf( 'Author #%d', $this->item->id); ?></legend>
<table class="admintable">
  <tbody>
    <tr>
      <td width="150">&nbsp;</td>
      <td class="key"><?php echo $fieldsMain['fullname']->label; ?></td>
      <td><?php echo $fieldsMain['fullname']->field; ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="key"><?php echo $fieldsMain['alias']->label; ?></td>
      <td><?php echo $fieldsMain['alias']->field; ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="key"><?php echo $fieldsMain['published']->label; ?></td>
      <td><?php echo $fieldsMain['published']->field; ?></td>
    </tr>
    <tr>
            <td>&nbsp;</td>
            <td class="key"><?php echo $fieldsMain['body']->label; ?></td>
            <td><?php echo $fieldsMain['body']->field; ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="key"><?php echo $fieldsMain['user_id']->label; ?></td>
            <td><?php echo $fieldsMain['user_id']->field; ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="key"><?php echo $fieldsMain['email']->label; ?></td>
            <td><?php echo $fieldsMain['email']->field; ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="key"><?php echo $fieldsMain['position']->label; ?></td>
            <td><?php echo $fieldsMain['position']->field; ?></td>
        </tr>
        <!--tr>
      <td>&nbsp;</td>
      <td class="key"><?php echo $fieldsMain['params']->label; ?></td>
      <td><?php echo $fieldsMain['params']->field; ?></td>
    </tr> -->
    <tr>
            <td>&nbsp;</td>
        <td colspan="2">
    <?php
       echo $this->mediaHTML;
    ?>
            </td>
        </tr>
    </tbody>
</table>
  </tbody>
</table>
<input type="hidden" name="option" value="com_hub2" /> <input
  type="hidden" name="model" value="author" /> <input type="hidden"
  name="view" value="author" /> <input type="hidden" name="return"
  value="author" /> <input type="hidden" name="task" value="" />
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
<style>
.invalid {color:red;}
input.invalid{border:1px solid red;}
</style>
<script type="text/javascript">
jQuery(function() {
    addCustomValidators();
    addTitleOnBlurHandler("jxform[fullname]","jxform[alias]");
});
</script>

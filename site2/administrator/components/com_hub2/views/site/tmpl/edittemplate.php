<?php

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
$fieldsMain		= $this->form->getFields('jxform');
$paramsGroup     = $this->form->getFields('jxform','params');
$fieldsParams = $paramsGroup['params'];
echo $this->form->getHead();
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
<legend><?php echo JText::sprintf( 'Site #%d', $this->item->id); ?></legend>
<table class="adminform">
	<tbody>
        <?php foreach ($fieldsMain as $field) { ?>
		<tr>
			<td width="150">&nbsp;</td>
			<td><?php echo $field->label; ?></td>
			<td><?php echo $field->field; ?></td>
		</tr>
            <?php } ?>
		<tr>
            <td>&nbsp;</td>
            <td><?php echo $fieldsParams->label?></td>
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
	</tbody>
</table>
<input type="hidden" name="option" value="com_hub2" /> <input
	type="hidden" name="model" value="site" /> <input type="hidden"
	name="view" value="site" /> <input type="hidden" name="return"
	value="site" /> <input type="hidden" name="task" value="" /></fieldset>
<?php if (is_object( $this->item)  && $this->item->id > 0) {
    echo '<input type="hidden" name="id" value="'.$this->item->id.'" />';
}
echo $this->form->getFoot(); ?>

<script language="javascript" type="text/javascript">
function trim(str, chars) {
    return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

    function submitbutton(task)
    {

        var form = document.adminForm;
        var error='';
        if (task == 'save') {
            var site_url=document.getElementById('jxform_url');

            if (document.getElementById('jxform_name').value.length <= 0 ||
                    document.getElementById('jxform_name').value.trim()== '') {
                error = "Site name is required";
            } else
            if (site_url && (site_url.value=='' || site_url.value.length<=0 ||
                    (site_url.value.substr(0,7).toLowerCase()!='http://'  &&
                     site_url.value.substr(0,8).toLowerCase()!='https://') ||
                     site_url.value.length<=8)){
                error='Site url invalid';
            } else
            if (document.getElementById('jxform_dbhost').value.length <= 0 ||
                    document.getElementById('jxform_dbhost').value.trim()== '') {
                error = "DB Host name is required";
            } else
            if (document.getElementById('jxform_dbname').value.length <= 0 ||
                    document.getElementById('jxform_dbname').value.trim()== '') {
                error = "DB name is required";
            } else
            if (document.getElementById('jxform_dbuser').value.length <= 0 ||
                    document.getElementById('jxform_dbuser').value.trim()== '') {
                error = "DB User name is required";
            } else
            if (document.getElementById('jxform_dbprefix').value.length <= 0 ||
                    document.getElementById('jxform_dbprefix').value.trim()== '') {
                error = "DB Prefix is required";
            }
        }
        if(error!=""){
            document.formvalidator.isValid(form);
            alert(error);
            return;
        }
        if (task == 'cancel' || document.formvalidator.isValid(document.adminForm)) {
            submitform(task);
        }
    }
</script>
<style>
.invalid {color:red;}
input.invalid{border:1px solid red;}
</style>
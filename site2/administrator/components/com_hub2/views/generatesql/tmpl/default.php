<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die();
?>
<script type="text/javascript">
/*function submitbutton(pressbutton) {
  if (pressbutton) {
    document.adminForm.task.value=pressbutton;
  }
  if (pressbutton == 'generateSQL') {
    // loading here
    h = new Element ('div', {
      'class': 'overlay',
      html: '<center>&nbsp;Loading page ... Please wait</center>',
      styles: {
        position: 'absolute',
        left: '0px',
        top:  '0px',
        width: '100%',
        height: '100%',
        'overflow': 'auto',
        'z-index': '99999',
        'opacity': '0.9',
        'background-color': 'gray',
        'color': 'white',
        'font-size': '20px'
      }
    });
    h.inject($(document.body));
  }
  if (typeof document.adminForm.onsubmit == "function") {
    document.adminForm.onsubmit();
  }
  document.adminForm.submit();
}*/
</script>
<form id="generate-sql-form" name="adminForm" method="post" action="">
<fieldset class="adminform">
<legend><?php echo JText::_( 'GENERATE_MULTIPLE_SQL' ); ?></legend>
<span style="color:red"><?php echo $this->state['msg']; ?></span>
<table class="adminform">
  <tbody>
    <tr>
      <td width="150">&nbsp;</td>
      <td class="key"><?php echo JText::_('SQL'); ?></td>
      <td><textarea name="sql_str" id="sqlstr" cols="90" rows="10"><?php if($this->state['sql_str'] != "") echo $this->state['sql_str']; ?></textarea></td>
    </tr>
    <tr>
    <td></td>
    <td></td>
    <td><pre>Ensure you use #__ for table names, and are using ; between multiple statements</pre></td>
    </tr>
  </tbody>
</table>
</fieldset>
<input type="hidden" name="option" value="com_hub2" />
<input type="hidden" name="task" value="generateSQL" />
</form>

<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<script type="text/javascript">
function submitbutton(pressbutton) {
  if (pressbutton) {
    document.adminForm.task.value=pressbutton;
  }
  if (pressbutton == 'pushConfig') {
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
}
</script>
<form id="compare-form" name="adminForm" method="post" action="">
<fieldset class="adminform">
<legend><?php echo JText::_( 'PUSH_CONFIG_FROM_TEMPLATE_CONFIG' ); ?></legend>
<table class="adminform">
  <tbody>
    <tr>
      <td width="150">&nbsp;</td>
      <td class="key"><?php echo JText::_( 'SELECT_TEMPLATE_SITE' ); ?></td>
      <td width="70%">
      <?php
            $default = $this->template_sites_default;
            $options = array();
            foreach($this->template_sites as $key=>$value) :
                $options[] = JHTML::_('select.option', $this->template_sites[$key]->id, $this->template_sites[$key]->name);
            endforeach;
      $dropdown = JHTML::_('select.genericlist', $options, 'template_sites', 'class="inputbox"', 'value', 'text',$default);
      echo $dropdown;
      ?></td>
    </tr>
  </tbody>
</table>
<div>
<?php
if(!empty($this->state)) {
    echo"<ul>";
    foreach($this->state as $i=>$s){
        if($s->success == 1) {
            echo "<li>Push succesful to {$i}</li>";
        } else {
            echo "<li>Push failed to {$i}</li>";
        }
    }
    echo"</ul>";
}
?>
</div>
</fieldset>
<input type="hidden" name="option" value="com_hub2" />
<input type="hidden" name="task" value="pushConfig" />
</form>
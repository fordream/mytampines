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
  if (pressbutton == 'runVerifyCoponentsModules') {
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
<legend><?php echo JText::_( 'COMPARE_TEMPLATE_SITE_WITH_OTHER_SITES' ); ?></legend>
<table class="adminform">
  <tbody>
    <tr>
      <td width="150">&nbsp;</td>
      <td class="key"><?php echo JText::_('TEMPLATE_SITES'); ?></td>
      <td>
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
    <tr>
      <td width="150">&nbsp;</td>
      <td class="key"><?php echo JText::_('QUERY_STRING'); ?></td>
      <td><textarea name="query_str" id="querystr" col="30" rows="5"><?php if($this->state['query_str'] != "") echo $this->state['query_str']; ?></textarea></td>
    </tr>
  </tbody>
</table>
<div>
<ul>
  <?php foreach($this->state['result'][1] as $k=>$v) { ?>
  <li>The subsite "<b><?php echo $k; ?></b>" has total <b><?php echo count($this->state['result'][0][0]); ?></b>
  signatures to count. Found	<?php if(count($this->state['result'][1][$k])> 0)
  {?>
    <span style="color:red;"><b><?php echo count($this->state['result'][1][$k]);?></b></span>
    issue(s) on the signature(s) <br /><span style="color:red;"><b>
    <ul>
    <?php foreach($this->state['result'][1][$k] as $k2=>$v2)
    {
        $temp =  str_replace("###-->", "", str_replace("<!--###", "", $v2));
        $disp = explode(":", $temp);
        echo "<li>".$disp[0]."</li>";
      }
      echo "</ul>";
  }
  else
    {?>
      <span style="color:green;"><b><?php echo count($this->state['result'][1][$k]);?></b></span> <?php
  }?></b> mismatch.</span>
  </li>
  <?php } ?>
</ul>
</div>
</fieldset>
<input type="hidden" name="option" value="com_hub2" />
<input type="hidden" name="task" value="runVerifyCoponentsModules" />
</form>
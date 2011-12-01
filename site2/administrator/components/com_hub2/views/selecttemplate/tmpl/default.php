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
<h1>Menu items for spokes are managed in 3 steps:</h1>
<ul>
<li>Step 1: Select a template site below and login to its administrator</li>
<li>Step 2: Change the menu on the template site that is related to the set of spokes</li>
<li>Step 3: Copy the configuration from the template site to the its related spokes (via the Hub2 Dashboard)</li>
</ul>
<fieldset class="adminform"><legend><?php echo JText::_( 'Step 1: Select a Template Site' ); ?></legend>
<table class="adminform">
	<tbody>
	<?php
	foreach($this->template_sites as $key=>$value) :?>
	<tr>
	<td>
	<?php echo '<h2><a href="index.php?option=com_hub2&task=selectTemplateForMenuManagement&layout=showiframe&tmpl=component&url='.base64_encode($this->template_sites[$key]->url.'/administrator').'">'.$this->template_sites[$key]->name.'</a></h2>';?>
	</td>
	</tr> <?php
	endforeach;
	?>
	</tbody>
</table>
</fieldset>
</form>
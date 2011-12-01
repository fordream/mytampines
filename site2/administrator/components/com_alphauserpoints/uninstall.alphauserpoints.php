<?php 
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2011 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');	
 
class Status {
	var $STATUS_FAIL = 'Failed';
	var $STATUS_SUCCESS = 'Success';
	var $infomsg = array();
	var $errmsg = array();
	var $status;
}

$db =& JFactory::getDBO();
$install_status = array();

// Uninstall mod_aupadmin
$query = "SELECT id FROM #__modules WHERE module='mod_aupadmin'";
$db->setQuery($query);
$aupmodule = $db->loadObject();
$module_installer = new JInstaller;
$status = new Status();
$status->status = $status->STATUS_FAIL;
if($module_installer->uninstall('module', $aupmodule->id))
{
	$status->status = $status->STATUS_SUCCESS;
}
$install_status['mod_aupadmin'] = $status;
	
// Uninstall plugin system
$query = "SELECT id FROM #__plugins WHERE element='alphauserpoints' LIMIT 1";
$db->setQuery($query);
$aupplugin = $db->loadResult();
if ( $aupplugin ) {
	$plugin_installer = new JInstaller;
	$status = new Status();
	$status->status = $status->STATUS_FAIL;
	if($plugin_installer->uninstall('plugin', $aupplugin))
	{
		$status->status = $status->STATUS_SUCCESS;
	}	
} else $status->status = $status->STATUS_FAIL;
$install_status['Plugin-System-alphauserpoints'] = $status;

// Uninstall plugin user
$query = "SELECT id FROM #__plugins WHERE element='sysplgaup_newregistered' LIMIT 1";
$db->setQuery($query);
$aupplugin = $db->loadResult();
if ( $aupplugin ) {
	$plugin_installer = new JInstaller;
	$status = new Status();
	$status->status = $status->STATUS_FAIL;
	if($plugin_installer->uninstall('plugin', $aupplugin))
	{
		$status->status = $status->STATUS_SUCCESS;
	}	
} else $status->status = $status->STATUS_FAIL;
$install_status['Plugin-User-sysplgaup_newregistered'] = $status;

// Uninstall plugin content
$query = "SELECT id FROM #__plugins WHERE element='sysplgaup_reader2author' LIMIT 1";
$db->setQuery($query);
$aupplugin = $db->loadResult();
if ( $aupplugin ) {
	$plugin_installer = new JInstaller;
	$status = new Status();
	$status->status = $status->STATUS_FAIL;
	if($plugin_installer->uninstall('plugin', $aupplugin))
	{
		$status->status = $status->STATUS_SUCCESS;
	}	
} else $status->status = $status->STATUS_FAIL;
$install_status['Plugin-Content-sysplgaup_reader2author'] = $status;

// Uninstall plugin content
$query = "SELECT id FROM #__plugins WHERE element='sysplgaup_raffle' LIMIT 1";
$db->setQuery($query);
$aupplugin = $db->loadResult();
if ( $aupplugin ) {
	$plugin_installer = new JInstaller;
	$status = new Status();
	$status->status = $status->STATUS_FAIL;
	if($plugin_installer->uninstall('plugin', $aupplugin))
	{
		$status->status = $status->STATUS_SUCCESS;
	}	
} else $status->status = $status->STATUS_FAIL;
$install_status['Plugin-Content-sysplgaup_raffle'] = $status;

// Uninstall plugin content
$query = "SELECT id FROM #__plugins WHERE element='sysplgaup_content' LIMIT 1";
$db->setQuery($query);
$aupplugin = $db->loadResult();
if ( $aupplugin ) {
	$plugin_installer = new JInstaller;
	$status = new Status();
	$status->status = $status->STATUS_FAIL;
	if($plugin_installer->uninstall('plugin', $aupplugin))
	{
		$status->status = $status->STATUS_SUCCESS;
	}	
} else $status->status = $status->STATUS_FAIL;
$install_status['Plugin-Content-sysplgaup_content'] = $status;



// Uninstall plugin for Editor (button to insert Raffle ID)
$query = "SELECT id FROM #__plugins WHERE element='raffle' LIMIT 1";
$db->setQuery($query);
$aupplugin = $db->loadResult();
if ( $aupplugin ) {
	$plugin_installer = new JInstaller;
	$status = new Status();
	$status->status = $status->STATUS_FAIL;
	if($plugin_installer->uninstall('plugin', $aupplugin))
	{
		$status->status = $status->STATUS_SUCCESS;
	}	
} else $status->status = $status->STATUS_FAIL;
$install_status['Plugin-Editor-Raffle'] = $status;


function com_uninstall() {	
	echo( "AlphaUserPoints has been successfully uninstalled." );	
}
?>
<h1>AlphaUserPoints Uninstallation</h1>
<table class="adminlist">
	<thead>
		<tr>
			<th width="20">&nbsp;</th>
			<th class="title"><?php echo JText::_('Sub Component'); ?></th>
			<th width="60%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
		$i=0;
		foreach ( $install_status as $component => $status ) {
			$alerticon = ($status->status == $status->STATUS_SUCCESS)? 'tick.png' : 'publish_x.png'; 
		?>
		<tr class="row<?php echo $i; ?>">
			<td class="key"><?php echo '<img src="images/'.$alerticon.'" alt="" />'; ?></td>
			<td class="key"><?php echo $component; ?></td>
			<td>
				<?php echo ($status->status == $status->STATUS_SUCCESS)? '<strong>'.JText::_('Uninstalled').'</strong>' : '<em>'.JText::_('NOT Uninstalled').'</em>'?>
				<?php if (count($status->errmsg) > 0 ) {
						foreach ( $status->errmsg as $errmsg ) {
       						echo '<br/>Error: ' . $errmsg;
						}
				} ?>
				<?php if (count($status->infomsg) > 0 ) {
						foreach ( $status->infomsg as $infomsg ) {
       						echo '<br/>Info: ' . $infomsg;
						}
				} ?>
			</td>
		</tr>	
	<?php
			if ($i=0){ $i=1;} else {$i = 0;}; 
		}?>		
	</tbody>
</table>
<?php
/**
 * @version		$Id: install.php 52 2008-01-17 01:25:06Z andrew.eddie $
 * @package		JXtended.Control
 * @subpackage	com_control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

// load the component language file
$language = &JFactory::getLanguage();
$language->load('com_control');

//$nPaths = $this->_paths;
$status = new JObject();
$status->modules = array();
$status->plugins = array();

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * MODULE INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$modules = &$this->manifest->getElementByPath('modules');
if (is_a($modules, 'JSimpleXMLElement') && count($modules->children())) {

	foreach ($modules->children() as $module)
	{
		$mname		= $module->attributes('module');
		$mclient	= JApplicationHelper::getClientInfo($module->attributes('client'), true);

		// Set the installation path
		if (!empty ($mname)) {
			$this->parent->setPath('extension_root', $mclient->path.DS.'modules'.DS.$mname);
		} else {
			$this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('No module file specified'));
			return false;
		}

		/*
		 * If the module directory already exists, then we will assume that the
		 * module is already installed or another module is using that directory.
		 */
		if (file_exists($this->parent->getPath('extension_root'))&&!$this->parent->getOverwrite()) {
			$this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('Another module is already using directory').': "'.$this->parent->getPath('extension_root').'"');
			return false;
		}

		// If the module directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		 * Since we created the module directory and will want to remove it if
		 * we have to roll back the installation, lets add it to the
		 * installation step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		$element = &$module->getElementByPath('files');
		if ($this->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy language files
		$element = &$module->getElementByPath('languages');
		if ($this->parent->parseLanguages($element, $mclient->id) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy media files
		$element = &$module->getElementByPath('media');
		if ($this->parent->parseMedia($element, $mclient->id) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		$mtitle		= $module->attributes('title');
		$mposition	= $module->attributes('position');

		if ($mtitle && $mposition) {
			$row = & JTable::getInstance('module');
			$row->title		= $mtitle;
			$row->ordering	= $row->getNextOrder("position='".$mposition."'");
			$row->position	= $mposition;
			$row->showtitle	= 0;
			$row->iscore	= 0;
			$row->access	= ($mclient->id) == 1 ? 2 : 0;
			$row->client_id	= $mclient->id;
			$row->module	= $mname;
			$row->published	= 1;
			$row->params	= '';

			if (!$row->store()) {
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.$db->stderr(true));
				return false;
			}
		}

		$status->modules[] = array('name'=>$mname,'client'=>$mclient->name);
	}
}


/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * PLUGIN INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$plugins = &$this->manifest->getElementByPath('plugins');
if (is_a($plugins, 'JSimpleXMLElement') && count($plugins->children())) {

	foreach ($plugins->children() as $plugin)
	{
		$pname		= $plugin->attributes('plugin');
		$pgroup		= $plugin->attributes('group');

		// Set the installation path
		if (!empty($pname) && !empty($pgroup)) {
			$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.$pgroup);
		} else {
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('No plugin file specified'));
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// If the plugin directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		 * If we created the plugin directory and will want to remove it if we
		 * have to roll back the installation, lets add it to the installation
		 * step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		$element = &$plugin->getElementByPath('files');
		if ($this->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy all necessary files
		$element = &$plugin->getElementByPath('languages');
		if ($this->parent->parseLanguages($element, 1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy media files
		$element = &$plugin->getElementByPath('media');
		if ($this->parent->parseMedia($element, 1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */
		$db = &JFactory::getDBO();

		// Check to see if a plugin by the same name is already installed
		$query = 'SELECT `id`' .
				' FROM `#__plugins`' .
				' WHERE folder = '.$db->Quote($pgroup) .
				' AND element = '.$db->Quote($pname);
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$id = $db->loadResult();

		// Was there a plugin already installed with the same name?
		if ($id) {

			if (!$this->parent->getOverwrite())
			{
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Plugin').' "'.$pname.'" '.JText::_('already exists!'));
				return false;
			}

		} else {
			$row =& JTable::getInstance('plugin');
			$row->name = JText::_(ucfirst($pgroup)).' - '.JText::_(ucfirst($pname));
			$row->ordering = 0;
			$row->folder = $pgroup;
			$row->iscore = 0;
			$row->access = 0;
			$row->client_id = 0;
			$row->element = $pname;
			$row->published = 1;
			$row->params = '';

			if (!$row->store()) {
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
				return false;
			}
		}

		$status->plugins[] = array('name'=>$pname,'group'=>$pgroup);
	}
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * SETUP DEFAULTS
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

// Insert a new installation record in the version log if no rows are present.
$db	= &JFactory::getDBO();
$db->setQuery('SELECT COUNT(`version`) FROM `#__jxcontrol`');

if ($db->loadResult() == 0)
{
	// Checking for the old version table
	if (in_array($db->getPrefix().'jxcontrol', $db->getTableList()))
	{
		// Migrate the old version information
		$db->setQuery(
			'INSERT INTO #__jxtended (`extension`,`version`, `installed_date`, `log`)'
			.' SELECT '.$db->quote('com_control').', `version`, `installed_date`, `log`'
			.'  FROM `#__jxcontrol` ORDER BY `installed_date`'
		);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('JXtended Version Migration').': '.$db->stderr(true));
			return false;
		}

		// Drop the old version table
		$db->setQuery(
			'DROP TABLE `#__jxcontrol`'
		);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('JXtended Version Migration').': '.$db->stderr(true));
			return false;
		}
	}
	else {
		// Vanilla new install
		$db->setQuery(
			'INSERT IGNORE INTO `#__jxtended` (`extension`,`version`,`log`)' .
			' VALUES ('.$db->quote('com_control').','.$db->Quote($version->version.'.'.$version->subversion.$version->status).', '.$db->Quote('New Install').')'
		);
		$db->query();
	}
}

// Correct bug in components table for backend only extenions

$db->setQuery(
	'UPDATE `#__components` SET `link` = '.$db->quote('').' WHERE `option` = '.$db->quote('com_control')
);
if (!$db->query()) {
	JError::raiseWarning(500, $db->getErrorMsg());
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * OUTPUT TO SCREEN
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
$rows = 0;
?>
<h1>Control Installation</h1>

<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo JText::_('Control').' '.JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
<?php if (count($status->modules)) : ?>
		<tr>
			<th><?php echo JText::_('Module'); ?></th>
			<th><?php echo JText::_('Client'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->modules as $module) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif;
if (count($status->plugins)) : ?>
		<tr>
			<th><?php echo JText::_('Plugin'); ?></th>
			<th><?php echo JText::_('Group'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->plugins as $plugin) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif; ?>
	</tbody>
</table>

<?php
/**
 * @version		$Id: setup.php 1178 2009-06-04 01:39:04Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');
require_once JPATH_ADMINISTRATOR.'/components/com_control/version.php';

/**
 * Setup Model
 *
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlModelSetup extends JModel
{
	/**
	 * Method to manually install the extension
	 *
	 * @return	boolean	True on success.
	 */
	public function install()
	{
		// Get the current component version information.
		$version = new ControlVersion();
		$current = $version->version.'.'.$version->subversion.$version->status;

		// Get the database object.
		$db = &$this->_db;

		// Get the number of relevant rows in the components table.
		$db->setQuery(
			'SELECT COUNT(id)' .
			' FROM `#__components`' .
			' WHERE `option` = "com_control"'
		);
		$installed = $db->loadResult();

		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Check to see if the component is installed.
		if ($installed > 0) {
			$this->setError(JText::_('JX_Setup_Already_Installed'));
			return false;
		}

		// Attempt to add the necessary rows to the components table.
		$db->setQuery(
			'INSERT INTO `#__components` VALUES' .
			' (0, "Control", "", 0, 0, "option=com_control", "Control", "com_control", 0, "components/com_control/media/images/icon-16-jx.png", 0, "", 1)'
		);
		$db->query();

		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Verify the schema file.
		$file = JPATH_ADMINISTRATOR.'/components/com_control/install/installsql.mysql.utf8.php';
		if (!JFile::exists($file)) {
			$this->setError(JText::_('JX_Setup_Schema_File_Missing'));
			return false;
		}

		// Set the SQL from the schema file.
		$db->setQuery(JFile::read($file));

		// Attempt to import the component schema.
		$return = $db->queryBatch(false);

		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Attempt to insert the manual install entry into the component version table.
		$db->setQuery(
			'INSERT IGNORE INTO `#__taoj` (`extension`,`version`,`log`)' .
			' VALUES ('.$db->quote('com_control').','.$db->Quote($current).', '.$db->Quote('JX_Setup_Manual_Install').')'
		);
		$db->query();

		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}

	/**
	 * Method to run necessary database upgrade scripts
	 *
	 * @return	boolean	True on success.
	 */
	public function upgrade()
	{
		// Get the component upgrade information.
		$version	= new ControlVersion();
		$upgrades	= $version->getUpgrades();

		// If there are upgrades to process, attempt to process them.
		if (is_array($upgrades) && count($upgrades))
		{
			// Sort the upgrades, lowest version first.
			uksort($upgrades, 'version_compare');

			// Get the database object.
			$db = &$this->_db;

			// Get the number of relevant rows in the components table.
			$db->setQuery(
				'SELECT COUNT(id)' .
				' FROM `#__components`' .
				' WHERE `option` = "com_control"'
			);
			$installed = $db->loadResult();

			// Check for a database error.
			if ($db->getErrorNum()) {
				$this->setError($db->getErrorMsg());
				return false;
			}

			// Check to see if the component is installed.
			if ($installed < 1) {
				$this->setError(JText::_('JX_Setup_Not_Installed'));
				return false;
			}

			foreach ($upgrades as $upgradeVersion => $file)
			{
				$file = JPATH_COMPONENT.DS.'install'.DS.$file;

				if (JFile::exists($file))
				{
					// Set the upgrade SQL from the file.
					$db->setQuery(JFile::read($file));

					// Execute the upgrade SQL.
					$return = $db->queryBatch(false);

					// Check for a database error.
					if ($db->getErrorNum()) {
						$this->setError(JText::sprintf('JX_Setup_Database_Upgrade_Failed', $db->getErrorMsg()));
						return false;
					}

					// Upgrade was successful, attempt to log it to the versions table.
					$db->setQuery(
						'INSERT INTO `#__jxtended` (`extension`,`version`,`log`) VALUES' .
						' ('.$db->quote('com_control').','.$db->quote($upgradeVersion).', '.$db->quote(JText::sprintf('JX_Setup_Database_Upgrade_Version', $upgradeVersion)).')'
					);
					$db->query();

					// Check for a database error.
					if ($db->getErrorNum()) {
						$this->setError(JText::sprintf('JX_Setup_Database_Upgrade_Failed', $db->getErrorMsg()));
						return false;
					}
				}
			}
		}

		return true;
	}

	/**
	 * Method to enable the JXtended Libraries plugin.
	 *
	 * @access	public
	 * @return	boolean	True on success.
	 * @since	1.2
	 */
	function enableLibraries()
	{
		// Check if the plugin file is present.
		if (!file_exists(JPATH_ROOT.'/plugins/system/jxtended.php')) {
			$this->setError(JText::_('JX_Libraries_Missing'));
			return false;
		}

		// Get the database object.
		$db = &$this->_db;

		// Get the plugin information from the database.
		$db->setQuery(
			'SELECT `id`, `published`' .
			' FROM `#__plugins`' .
			' WHERE `folder` = "system"' .
			' AND `element` = "jxtended"'
		);
		$plugin = $db->loadObject();

		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Check to see if the plugin is installed.
		if (empty($plugin))
		{
			// Create a plugin row to for installation.
			$row = & JTable::getInstance('plugin');
			$row->name = JText::_('System - JXtended');
			$row->ordering = 0;
			$row->folder = 'system';
			$row->iscore = 0;
			$row->access = 0;
			$row->client_id = 0;
			$row->element = 'jxtended';
			$row->published = 1;
			$row->params = '';

			// Attempt to install the plugin.
			if (!$row->store())
			{
				// Install failed, set the error and return false.
				$this->setError(JText::_('JX_Unable_To_Install_Plugin'));
				return false;
			}

			return true;
		}

		// Check to see if the plugin is published.
		if (!$plugin->published)
		{
			// Attempt to publish the plugin.
			$db->setQuery(
				'UPDATE `#__plugins`' .
				' SET `published` = 1' .
				' WHERE `folder` = "system"' .
				' AND `element` = "jxtended"'
			);
			$db->query();

			// Check for a database error.
			if ($db->getErrorNum()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
}
<?php
/**
 * @version		$Id: object.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlControllerObject extends JController
{
	/**
	 * Proxy for getModel
	 */
	function &getModel()
	{
		return parent::getModel('Object', 'ControlModel', array('ignore_request' => true));
	}

	/**
	 * Method to run batch opterations.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function batch()
	{
		// Get variables from the request.
		$vars	= JRequest::getVar('batch', array(), 'post', 'array');
		$cid	= JRequest::getVar('cid', null, 'post', 'array');
		$type	= JRequest::getVar('object_type', null, 'post', 'word');

		$this->setRedirect('index.php?option=com_control&view=objects&type='.$type);

		if (empty($cid)) {
			return JError::raiseWarning(500, 'JX No Items Selected');
		}

		$model = &$this->getModel();
		$result = $model->batch($vars, $cid, $type);
		if (JError::isError($result)) {
			return JError::raiseWarning(500, $result->getMessage());
		}
	}
}

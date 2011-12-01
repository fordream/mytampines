<?php
/**
 * @version		$Id: test.php 1163 2009-05-22 23:15:43Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 */

// no direct access
defined('_JEXEC') or die;

jximport('jxtended.application.component.model');
jximport('jxtended.database.query');

/**
 * @package		JXtended.Control
 * @subpackage	com_control
 */
class ControlModelTest extends JXModel
{
	function getSections()
	{
		JModel::addIncludePath(JPATH_COMPONENT.DS.'models');

		$model					= JModel::getInstance('Section', 'ControlModel');
		$vars['select']			= 'a.value, a.name AS text';
		$vars['section_type']	= 'acl';
		$vars['order by']		= 'a.order_value,a.name';
		$options				= $model->getList($vars);
		return $options;
	}

	function getAxoSections()
	{
		JModel::addIncludePath(JPATH_COMPONENT.DS.'models');

		$model					= JModel::getInstance('Section', 'ControlModel');
		$vars['select']			= 'a.value, a.name AS text';
		$vars['section_type']	= 'axo';
		$vars['order by']		= 'a.order_value,a.name';
		$options				= $model->getList($vars);
		return $options;
	}

	function get2d()
	{
		$section	= $this->getState('section_value');
		$search		= $this->getState('search');

		$acl	= &JFactory::getACL();
		$db		= &$this->getDBO();
		$query	= new JXQuery;

		$query->select('a.value AS a_value, a.name AS a_name');
		$query->select('b.value AS b_value, b.name AS b_name');
		$query->select('c.value AS c_value, c.name AS c_name');
		$query->select('d.value AS d_value, d.name AS d_name');
		$query->from('#__core_acl_aco_sections a');
		$query->join('LEFT', '#__core_acl_aco b ON a.value=b.section_value, #__core_acl_aro_sections c');
		$query->join('LEFT', '#__core_acl_aro d ON c.value=d.section_value');
		$query->where('b.acl_type = 0');
		$query->order('a.value, b.value, c.value, d.value');

		// Conditional filtering
		if ($section) {
			$query->where('a.value = '.$db->Quote($section));
		}
		if ($search) {
			$serach = $db->Quote('%'.$db->getEscaped($search, true).'%', false);
			$query->where('d.name LIKE '.$serach);
		}

		$sql			= $query->toString();
		$db->setQuery($sql);
		$db->query();
		$this->_total	= $db->getNumRows();

		$limit	= (int) $this->getState('limit', 20);
		$offset	= (int) $this->getState('limitstart', 0);
		$db->setQuery($sql, $offset, $limit);
		$temp	= $db->loadRowList();
		$result	= array();

		foreach ($temp as $test)
		{
		    list(	$aco_section_value,
					$aco_section_name,
					$aco_value,
					$aco_name,

					$aro_section_value,
					$aro_section_name,
					$aro_value,
					$aro_name
				) = $test;
			$obj	= new stdClass;

			$obj->aco_section_value	= $aco_section_value;
			$obj->aco_section_name	= $aco_section_name;
			$obj->aco_value			= $aco_value;
			$obj->aco_name			= $aco_name;
			$obj->aro_section_value	= $aro_section_value;
			$obj->aro_section_name	= $aro_section_name;
			$obj->aro_value			= $aro_value;
			$obj->aro_name			= $aro_name;

			//$acl_check_begin_time = $profiler->getMicroTime();
			$check	= $acl->acl_query($aco_section_value, $aco_value, $aro_section_value, $aro_value);
			//$acl_check_end_time = $profiler->getMicroTime();
			$obj->allow				= $check['allow'];
			$obj->return_value		= $check['return_value'];

			//$acl_check_time = ($acl_check_end_time - $acl_check_begin_time) * 1000;
			//$total_acl_check_time += $acl_check_time;
			$result[]	= $obj;
		}
		return $result;
	}

	function get3d()
	{
		$section	= $this->getState('section_value');
		$axoSection	= $this->getState('axo_section_value');
		$search		= $this->getState('search');

		$acl	= &JFactory::getACL();
		$db		= &$this->getDBO();
		$query	= new JXQuery;

		$query->select('a.value AS a_value, a.name AS a_name');
		$query->select('b.value AS b_value, b.name AS b_name');
		$query->select('c.value AS c_value, c.name AS c_name');
		$query->select('d.value AS d_value, d.name AS d_name');
		$query->select('e.value AS e_value, e.name AS e_name');
		$query->select('f.value AS f_value, f.name AS f_name');
		$query->from('#__core_acl_aco_sections a');
		$query->join('LEFT', '#__core_acl_aco b ON a.value=b.section_value, #__core_acl_aro_sections c');
		$query->join('LEFT', '#__core_acl_aro d ON c.value=d.section_value, #__core_acl_axo_sections e');
		$query->join('LEFT', '#__core_acl_axo f ON e.value=f.section_value');
		$query->where('b.acl_type = 1');
		$query->order('a.value, b.value, c.value, d.value, e.value, f.value');

		// Conditional filtering
		if ($section) {
			$query->where('a.value = '.$db->Quote($section));
		}
		if ($axoSection) {
			$query->where('e.value = '.$db->Quote($axoSection));
		}
		if ($search) {
			if (strpos($search, 'id:') === 0) {
				$query->where('d.value = '.(int) substr($search, 3));
			}
			else {
				$serach = $db->Quote('%'.$db->getEscaped($search, true).'%', false);
				$query->where('d.name LIKE '.$serach);
			}
		}

		$sql			= $query->toString();
		$db->setQuery($sql);
		$db->query();

		$this->_total	= $db->getNumRows();

		$limit	= (int) $this->getState('limit', 20);
		$offset	= (int) $this->getState('limitstart', 0);
		$db->setQuery($sql, $offset, $limit);
		$temp	= $db->loadRowList();
		$result	= array();

		foreach ($temp as $test)
		{
		    list(	$aco_section_value,
					$aco_section_name,
					$aco_value,
					$aco_name,

					$aro_section_value,
					$aro_section_name,
					$aro_value,
					$aro_name,

					$axo_section_value,
					$axo_section_name,
					$axo_value,
					$axo_name
				) = $test;
			$obj	= new stdClass;

			$obj->aco_section_value	= $aco_section_value;
			$obj->aco_section_name	= $aco_section_name;
			$obj->aco_value			= $aco_value;
			$obj->aco_name			= $aco_name;
			$obj->aro_section_value	= $aro_section_value;
			$obj->aro_section_name	= $aro_section_name;
			$obj->aro_value			= $aro_value;
			$obj->aro_name			= $aro_name;
			$obj->axo_section_value	= $axo_section_value;
			$obj->axo_section_name	= $axo_section_name;
			$obj->axo_value			= $axo_value;
			$obj->axo_name			= $axo_name;

			//$acl_check_begin_time = $profiler->getMicroTime();
			$check	= $acl->acl_query($aco_section_value, $aco_value, $aro_section_value, $aro_value, $axo_section_value, $axo_value);
			//$acl_check_end_time = $profiler->getMicroTime();
			$obj->allow				= $check['allow'];
			$obj->return_value		= $check['return_value'];

			//$acl_check_time = ($acl_check_end_time - $acl_check_begin_time) * 1000;
			//$total_acl_check_time += $acl_check_time;
			$result[]	= $obj;
		}
		return $result;
	}

	function getDebug()
	{
		$acl	= &JFactory::getACL();
		$db		= &$this->getDBO();
		$query	= new JXQuery;

		$result = $acl->acl_query(
			JRequest::getVar('aco_section_value'),
			JRequest::getVar('aco_value'),
			JRequest::getVar('aro_section_value'),
			JRequest::getVar('aro_value'),
			JRequest::getVar('axo_section_value'),
			JRequest::getVar('axo_value'),
			JRequest::getVar('root_aro_group_id'),
			JRequest::getVar('root_axo_group_id'),
			true);

		// Grab all relavent columns
		$query = str_replace(	'a.id,a.allow,a.return_value',
								'	a.id,
									a.allow,
									a.return_value,
									a.note,
									a.updated_date,
									ac.section_value as aco_section_value,
									ac.value as aco_value,
									ar.section_value as aro_section_value,
									ar.value as aro_value,
									ax.section_value as axo_section_value,
									ax.value as axo_value',
									$result['query']);
		$db->setQuery($query);
		$result	= $db->loadObjectList();
//echo '<pre>'.$query.'</pre>';
		return $result;
	}
}
<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class alphauserpointsModelArchive extends Jmodel {

	function __construct(){
		parent::__construct();
	}
	
	function _archive()
	{
		$app = JFactory::getApplication();
		
		$db =& JFactory::getDBO();
		
		$dateCombine = JRequest::getVar('datestart', '', 'post', 'string');
		$count = '0';
		
		if ( $dateCombine!='' ) 
		{
			$dateCombine = $dateCombine . ' 00:00:00';
			
			// create a combined activity
			$query ="SELECT SUM(points) AS sumAllPoints, referreid FROM #__alpha_userpoints_details WHERE insert_date < '".$dateCombine."' GROUP BY referreid";
			$db->setQuery($query );
			$resultCombined = $db->loadObjectList();			
			// archive
			$query ="INSERT INTO #__alpha_userpoints_details_archive SELECT * FROM #__alpha_userpoints_details AS a WHERE a.insert_date < '".$dateCombine."'";
			$db->setQuery($query );
			$db->query();
			// delete			
			$query ="DELETE FROM #__alpha_userpoints_details WHERE insert_date < '".$dateCombine."'";
			$db->setQuery($query );
			$db->query();
			// optimize
			$query ="OPTIMIZE TABLE #__alpha_userpoints_details";
			$db->setQuery($query );
			$db->query();			
			
			JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'tables');
			
			// retreive ID for rule Archive
			$id_archive_rule = getIdPluginFunction( 'sysplgaup_archive' );
			
			if ( $id_archive_rule ) 
			{
				foreach ( $resultCombined as $combined ) 
				{
					if ( $combined->referreid!='' ) 
					{
						// save new points into alpha_userpointsdetails table
						$row =& JTable::getInstance('userspointsdetails');
						$row->id				= NULL;
						$row->referreid			= $combined->referreid;
						$row->points			= $combined->sumAllPoints;
						$row->insert_date		= $dateCombine;
						$row->expire_date 		= '';		
						$row->rule				= intval($id_archive_rule);
						$row->approved			= 1;
						$row->status			= 1;
						$row->keyreference		= '';
						$row->datareference		= sprintf ( JText::_('AUP_COMBINED_ACTIVITIES_BEFORE_DATE'), JHTML::_('date', $dateCombine, JText::_('DATE_FORMAT_LC2'), 0) );
						if ( !$row->store() )
						{
							JError::raiseError(500, $row->getError());
							return;
						}
					}
				}			
				$app->enqueueMessage( JText::_('AUP_ACTIVITIES_COMBINED_SUCCESSFULLY') );
			} else {
				$app->enqueueMessage( JText::_('AUP_CRITICAL_ERROR_SYSTEM') );
			}
		} else {			
			$app->enqueueMessage( JText::_('AUP_MISSING_DATE') );
		}
		
		return;

	}
	
}
?>
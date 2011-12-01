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

class alphauserpointsModelCouponcodes extends Jmodel {

	function __construct(){
		parent::__construct();
	}
	
	function _load_couponcodes() {
		$app = JFactory::getApplication();
		
		$db			    =& JFactory::getDBO();
				
		$total 			= 0;
		
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);		

		$query = "SELECT * FROM #__alpha_userpoints_coupons";
		$total = @$this->_getListCount($query);
		$result = $this->_getList($query, $limitstart, $limit);
		return array($result, $total, $limit, $limitstart);
	
	}
	
	
	function _edit_coupon() {
	
		$db     =& JFactory::getDBO();

		$cid 	= JRequest::getVar('cid', array(0));
		$option = JRequest::getVar('option');
		
		if (!is_array( $cid )) {
			$cid = array(0);
		}

		$row =& JTable::getInstance('coupons');
		$row->load( $cid[0] );
		
		$lists = array();
		$lists['public'] 		= JHTML::_('select.booleanlist',  'public', 'class="inputbox"', $row->public );
		
		return array($row, $lists);
	
	}
	
	
	function _delete_coupon() {
		$app = JFactory::getApplication();

		// initialize variables
		$db			=& JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'post', 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {		

			$query = "DELETE FROM #__alpha_userpoints_coupons"
					. "\n WHERE (`id` = " . implode(' OR `id` = ', $cid) . ")"
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg();
				$msgType = 'error';
			} else $msg = JText::_('AUP_SUCCESSFULLYDELETED');

		}

		$app->redirect('index.php?option=com_alphauserpoints&task=couponcodes', $msg, $msgType);
		
	}
	
	function _save_coupon() {
		$app = JFactory::getApplication();

		// initialize variables
		$db =& JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		$row =& JTable::getInstance('coupons');
		
		if ( $post['couponcode']=='' ) {
			$post['couponcode']= $random = $this->createRandomCode();
		}
		
		if (!$row->bind( $post )) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = JText::_( 'AUP_DETAILSSAVED' );
		$app->redirect( 'index.php?option=com_alphauserpoints&task=couponcodes', $msg );
	}
	
	function _save_coupongenerator() {
		$app = JFactory::getApplication();

		// initialize variables
		$db =& JFactory::getDBO();
		$post	= JRequest::get( 'post' );	
		
		$numbercouponcode	= JRequest::getVar('numbercouponcode', 20, 'post', 'int');	
		$numrandomchars		= JRequest::getVar('numrandomchars', 0, 'post', 'int');
		$enabledincrement	= intval(JRequest::getVar('enabledincrement', 0, 'post', 'int'));		
		
		if ( $post['points'] )  {
			for ($i=0, $n=$numbercouponcode; $i < $n; $i++) {
			
				$row =& JTable::getInstance('coupons');
				
				$row->id = NULL;
				
				$couponcode = "";
				$couponcode .= $post['prefixcouponcode'];
				if ( $numrandomchars   ) $couponcode .= $this->createRandomCode($numrandomchars);					
				if ( $enabledincrement ) $couponcode .= ($i+1);
				$row->couponcode = $couponcode;
				$row->description = $post['description'] ;
				$row->points = $post['points'] ;			
				$row->expires = $post['expires'] ;
				$row->public = $post['public'] ;
				if ( $couponcode!='' ) {
					if (!$row->store()) {
						echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
						exit();
					}
				}		
			}
		}
		
		$app->redirect( 'index.php?option=com_alphauserpoints&task=couponcodes', $msg );
		$msg = JText::_( 'AUP_DETAILSSAVED' );
	}

	
	function createRandomCode($n=8) {
	
		$chars = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";	
		srand((double)microtime()*1000000);
	
		$i = 0;	
		$code = "";	
		$n = $n - 1;
		
		while ($i <= $n) {	
			$num = rand() % 33;	
			$tmp = substr($chars, $num, 1);	
			$code = $code . $tmp;	
			$i++;	
		}	
	
		return $code;	
	
	}

}
?>
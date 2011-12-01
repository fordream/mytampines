<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_( 'AUP_COUPON_CODES' ), 'addedit' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::save( 'savecoupon' );
JToolBarHelper::cancel( 'cancelcoupon' );
JToolBarHelper::help( 'screen.alphauserpoints', true );

$row = $this->row;
$lists = $this->lists;

JRequest::setVar( 'hidemainmenu', 1 );
?>
<Script Language="JavaScript">
function generatecode() {
   
    var length=8;
    var sCode = "";
   
    for (i=0; i < length; i++) {    
        numI = getRandomNum();
        while (checkPunc(numI))	{ numI = getRandomNum(); }        
        sCode = sCode + String.fromCharCode(numI);
    }
    
    document.adminForm.couponcode.value = sCode.toUpperCase();;    
    return true;
}

function getRandomNum() {        
    // between 0 - 1
    var rndNum = Math.random()
    // rndNum from 0 - 1000    
    rndNum = parseInt(rndNum * 1000);
    // rndNum from 33 - 127        
    rndNum = (rndNum % 94) + 33;            
    return rndNum;
}

function checkPunc(num) {
    
    if ((num >=33) && (num <=47)) { return true; }
    if ((num >=58) && (num <=64)) { return true; }    
    if ((num >=91) && (num <=96)) { return true; }
    if ((num >=123) && (num <=126)) { return true; }
    
    return false;
}
</Script>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'AUP_DETAILS' ); ?></legend>
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_CODE' ); ?>::<?php echo JText::_('AUP_CODE'); ?>">
					<?php echo JText::_( 'AUP_CODE' ); ?>:
				</span>
			</td>
		  <td>
			<input class="inputbox" type="text" name="couponcode" id="couponcode" size="20" maxlength="20" value="<?php echo $row->couponcode; ?>" />
			<input name="autogenerate" type="button" id="autogenerate" value="..." onclick="javascript:generatecode()">
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>::<?php echo JText::_('AUP_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>:
				</span>
			</td>
			<td>
			<input class="inputbox" type="text" name="description" id="description" size="100" maxlength="255" value="<?php echo $row->description; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS' ); ?>::<?php echo JText::_('AUP_POINTS'); ?>">
					<?php echo JText::_( 'AUP_POINTS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="points" id="points" size="20" value="<?php echo $row->points; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_EXPIRE' ); ?>::<?php echo JText::_('AUP_EXPIRE'); ?>">
					<?php echo JText::_( 'AUP_EXPIRE' ); ?>:
				</span>
			</td>
			<td>
			<?php echo JHTML::_('calendar', $row->expires, 'expires', 'expires', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		<tr>
		  <td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_PUBLIC' ); ?>::<?php echo JText::_('AUP_PUBLIC'); ?>">
					<?php echo JText::_( 'AUP_PUBLIC' ); ?>:
				</span>
		  </td>
		  <td><?php echo $lists['public']; ?></td>
		  </tr>
		</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="redirect" value="couponcodes" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
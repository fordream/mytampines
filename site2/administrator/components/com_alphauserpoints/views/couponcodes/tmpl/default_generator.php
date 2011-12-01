<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<form action="index.php" method="post" name="adminForm" autocomplete="off">
		<fieldset>
			<div style="float: right">
				<button type="submit" onclick="window.parent.document.getElementById('sbox-window').close();window.top.location='index.php?option=com_alphauserpoints&task=couponcodes';">
					<?php echo JText::_( 'Save' );?></button>
				<button type="button" onclick="window.parent.document.getElementById('sbox-window').close();">
					<?php echo JText::_( 'Cancel' );?></button>
			</div>
			<div class="configuration" >
				<?php echo JText::_( 'AUP_COUPONS_GENERATOR' ); ?>
			</div>
		</fieldset>
<br /><br />
	<fieldset>
		<legend><?php echo JText::_( 'AUP_COUPONS_GENERATOR' ); ?></legend>
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_NUMBER' ); ?>::<?php echo JText::_('AUP_NUMBER_COUPON'); ?>">
					<?php echo JText::_( 'AUP_NUMBER' ); ?>:
				</span>
			</td>
		  <td>
			<input class="inputbox" type="text" name="numbercouponcode" id="numbercouponcode" size="20" maxlength="20" value="20" /><?php echo " " . JText::_('AUP_NUMBER_COUPON'); ?>
		  </td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_PREFIXE' ); ?>::<?php echo JText::_('AUP_PREFIXE'); ?>">
					<?php echo JText::_( 'AUP_PREFIXE' ); ?>:
				</span>
			</td>
		  <td>
			<input class="inputbox" type="text" name="prefixcouponcode" id="prefixcouponcode" size="20" maxlength="20" value="ABC-" />
		  </td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_NUMBER' ); ?>::<?php echo JText::_('AUP_NUMBER_RANDOM_CHARS'); ?>">
					<?php echo JText::_( 'AUP_NUMBER' ); ?>:
				</span>
			</td>
		  <td>
			  <select name="numrandomchars" id="numrandomchars">
			  <option value="0">0</option>
			  <option value="1">1</option>
			  <option value="2">2</option>
			  <option value="3">3</option>
			  <option value="4">4</option>
			  <option value="5">5</option>
			  <option value="6">6</option>
			  <option value="7">7</option>
			  <option value="8" selected>8</option>
			  <option value="9">9</option>
			  <option value="10">10</option>
			  <option value="12">12</option>
		    </select> <?php echo JText::_( 'AUP_NUMBER_RANDOM_CHARS' ); ?>
		  </td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_SUFFIXE' ); ?>::<?php echo JText::_('AUP_SUFFIXE'); ?>">
					<?php echo JText::_( 'AUP_SUFFIXE' ); ?>:
				</span>
			</td>
		  <td>
		    <input type="checkbox" name="enabledincrement" id="enabledincrement" value="1">
		    <?php echo JText::_( 'AUP_ENABLED_INCREMENT' ); ?>
		  </td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>::<?php echo JText::_('AUP_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>:
				</span>
			</td>
			<td>
			<input class="inputbox" type="text" name="description" id="description" size="100" maxlength="255" value="" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS' ); ?>::<?php echo JText::_('AUP_POINTS'); ?>">
					<?php echo JText::_( 'AUP_POINTS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="points" id="points" size="20" value="" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_EXPIRE' ); ?>::<?php echo JText::_('AUP_EXPIRE'); ?>">
					<?php echo JText::_( 'AUP_EXPIRE' ); ?>:
				</span>
			</td>
			<td>
			<?php echo JHTML::_('calendar', '', 'expires', 'expires', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		<tr>
		  <td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_PUBLIC' ); ?>::<?php echo JText::_('AUP_PUBLIC'); ?>">
					<?php echo JText::_( 'AUP_PUBLIC' ); ?>:
				</span>
		  </td>
		  <td><?php echo $this->lists['public']; ?></td>
		  </tr>
		</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="couponcode" value=""/>
	<input type="hidden" name="task" value="savecoupongenerator"/>	
</form>
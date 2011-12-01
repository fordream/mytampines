<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

$params = $this->params;
?>
<form action="index.php" method="post" name="adminForm" autocomplete="off">
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('saveconfiguration');window.top.setTimeout('window.parent.document.getElementById(\'sbox-window\').close()', 700);">
				<?php echo JText::_( 'AUP_SAVE' );?></button>
			<button type="button" onclick="window.parent.document.getElementById('sbox-window').close();">
				<?php echo JText::_( 'AUP_CANCEL' );?></button>
		</div>
		<div class="configuration" >
			<?php echo 'AlphaUserPoints'; ?>
		</div>
	</fieldset>

	<fieldset>
		<legend>
			<?php echo JText::_( 'AUP_CONFIGURATION' );?>
		</legend>
		<?php

		$pane = JPane::getInstance();
		echo $pane->startPane('config');

			echo $pane->startPanel(JText::_('AUP_GENERAL'), 'config.general');
			echo $params['general']->render();
			echo $pane->endPanel();
			
			echo $pane->startPanel(JText::_('AUP_INTEGRATION'), 'config.integration');
			echo $params['integration']->render();			
			echo $pane->endPanel();

		echo $pane->endPane();
		?>
	</fieldset>	
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="configuration" />
	<input type="hidden" name="tmpl" value="component" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
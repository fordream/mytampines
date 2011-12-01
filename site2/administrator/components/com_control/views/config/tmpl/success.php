<?php
/**
 * @version		$Id: success.php 1175 2009-06-04 00:15:16Z eddieajau $
 * @package		JXtended.Control
 * @copyright	Copyright (C) 2008 - 2009 JXtended LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

// no direct access
defined('_JEXEC') or die;

JHTML::_('behavior.mootools');
?>

<script type="text/javascript">
	window.addEvent('domready', function(){ window.parent.document.getElementById('sbox-window').close(); });
</script>
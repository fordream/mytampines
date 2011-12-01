<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
defined('_JEXEC') or die('Restricted access');

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_hub2" /> <input
    type="hidden" name="task" value="" /> <input type="hidden" name="view"
    value="backup" /> <input type="hidden" name="model" value="backup" /> <input type="hidden"
    name="<?php echo JUtility::getToken();?>" value="1" />
</form>
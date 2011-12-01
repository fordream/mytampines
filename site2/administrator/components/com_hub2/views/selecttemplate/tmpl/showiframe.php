<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$url = base64_decode(JRequest::getVar('url'));
?>
<h1>Step 2: Change menu on Template Site</h1>
<strong>Remember Step 3: <a href="index.php?option=com_hub2&task=pushConfigToSubsites">Click here</a> to go back to Hub and copy the configuration from hub</strong>
<iframe src="<?php echo $url?>" width="100%" height="100%"></iframe>
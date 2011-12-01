<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die();
$document = &JFactory::getDocument();
$document->addScript(JURI::root(true).'/components/com_hub2/js/min/download.jQuery.min.js');
// disable all links on click
?>
<script type="text/javascript">
var activeRequest = false;
function disablehref(el){
  if (activeRequest) {
    return false;
  }
    var input = document.getElementsByTagName("a");
    var count = input.length;
    for(var i =0; i < count; i++){
        if (input[i] != el) {
    input[i].disabled = true; // Does not disable the link it just gives it a grey color, but works with buttons
    input[i].removeAttribute("href"); //OBS this works but also stops the request and the next page does not get loaded, just hangs in the first page
        }
    input[i].style.cursor='wait'; // just to give the mousepointer the wait symbol instead of the hand
    }
    activeRequest = true;
    return true;
}
</script>

<div id="cpanel">
<h2>Site Configuration and Schema download</h2>
<div style="float: left;">
<div class="icon"><a href="javascript:void(0)" onclick="javascript:jQuery.download('index.php','option=com_hub2&task=dumpDBSchema');">
<img
  alt="<?php echo $this->escape( JText::_( 'GET_DB_SCHEMA' ) );?>"
  title="<?php echo $this->escape( JText::_( 'Download the Database Schema for this site' ) );?>"
  src="templates/khepri/images/header/icon-48-category.png">
<span><?php echo JText::_( 'GET_DB_SCHEMA' ); ?></span></a></div>
</div>
<div style="float: left;">
<div class="icon"><a href="javascript:void(0)" onclick="javascript:jQuery.download('index.php','option=com_hub2&task=dumpConfig');"> <img
  alt="<?php echo $this->escape(JText::_( 'GET_CONFIGURATION' )); ?>"
  title="<?php echo $this->escape( JText::_( 'Download the Joomla Configuration for this site' ) );?>"
  src="templates/khepri/images/header/icon-48-section.png">
<span><?php echo JText::_( 'GET_CONFIGURATION' ); ?></span></a></div>
</div>
<?php if (ISHUB) {?>
<div style="clear:both"></div>
<h2>Multisite Configuration Verification</h2>
<div style="float: left;">
<div class="icon"><a href="index.php?option=com_hub2&task=verifyConfigOnSites" onclick="javascript:return disablehref(this);"> <img
  alt="<?php echo $this->escape(JText::_( 'VERIFY_CONFIG_ACROSS_SITES' )); ?>"
  title="<?php echo $this->escape(JText::_( 'VERIFY_CONFIG_ACROSS_SITES' )); ?>"
  src="templates/khepri/images/header/icon-48-config.png">
<span><?php echo JText::_( 'VERIFY_CONFIG' ); ?></span></a></div>
</div>
<div style="float: left;">
<div class="icon"><a href="index.php?option=com_hub2&task=verifySchemaOnSites" onclick="javascript:return disablehref(this);"> <img
  alt="<?php echo $this->escape(JText::_( 'VERIFY_DB_SCHEMA_ACROSS_SITES' )); ?>"
  title="<?php echo $this->escape(JText::_( 'VERIFY_DB_SCHEMA_ACROSS_SITES' )); ?>"
  src="templates/khepri/images/header/icon-48-language.png">
<span><?php echo JText::_( 'VERIFY_DB_SCHEMA' ); ?></span></a></div>
</div>
<div style="float: left;">
<div class="icon"><a href="index.php?option=com_hub2&task=verifyComponentsModules" onclick="javascript:return disablehref(this);"> <img
  alt="<?php echo $this->escape(JText::_( 'VERIFY_COMPOMENTS_MODULES' )); ?>"
  title="<?php echo $this->escape(JText::_( 'VERIFY_COMPOMENTS_MODULES' )); ?>"
  src="templates/khepri/images/header/icon-48-component.png">
<span><?php echo JText::_( 'VERIFY_COMPOMENTS_MODULES' ); ?></span></a></div>
</div>
<div style="clear:both"></div>
<h2>Multisite Management</h2>
<div style="float: left;">
<div class="icon"><a href="index.php?option=com_hub2&task=pushToSubsites" onclick="javascript:return disablehref(this);"> <img
  alt="<?php echo $this->escape( JText::_( 'Copy configuration from a Template site to its related site(s)' )); ?>"
  title="<?php echo $this->escape( JText::_( 'Copy configuration from a Template site to its related site(s)' )); ?>"
  src="templates/khepri/images/header/icon-48-article.png">
<span><?php echo JText::_( 'Copy Configuration' ); ?></span></a></div>
</div>
<div style="float: left;">
<div class="icon"><a href="index.php?option=com_hub2&task=clearRouteCache" onclick="javascript:return disablehref(this);"> <img
    alt="<?php echo $this->escape(JText::_( 'Clear Cache from all sites' )); ?>"
    title="<?php echo $this->escape(JText::_( 'Clear Cache from all sites' )); ?>"
    src="templates/khepri/images/header/icon-48-article.png">
<span><?php echo JText::_( 'Clear Cache' ); ?></span></a></div>
</div>
<div style="float: left;">
<div class="icon"><a href="index.php?option=com_hub2&task=updateAndSetupDB" onclick="javascript:return disablehref(this);"> <img
    alt="<?php echo $this->escape(JText::_( 'Update Databases across all sites' )); ?>"
    title="<?php echo $this->escape(JText::_( 'Update Databases across all sites' )); ?>"
    src="templates/khepri/images/header/icon-48-component.png">
<span><?php echo JText::_( 'Update Databases' ); ?></span></a></div>
</div>
<div style="float: left;">
<div class="icon"><a href="index.php?option=com_hub2&task=generateSQL" onclick="javascript:return disablehref(this);"> <img
    alt="<?php echo $this->escape(JText::_( 'Convert to Multisite SQL' )); ?>"
    title="<?php echo $this->escape(JText::_( 'Convert a SQL string to another SQL string that can update all sites' )); ?>"
    src="templates/khepri/images/header/icon-48-module.png">
<span><?php echo JText::_( 'Convert to Multisite SQL' ); ?></span></a></div>
</div>
<div style="float: left;">
<div class="icon"><a href="index.php?option=com_hub2&task=selectTemplateForMenuManagement" onclick="javascript:return disablehref(this);"> <img
    alt="<?php echo $this->escape(JText::_( 'Spoke Menu Management' )); ?>"
    title="<?php echo $this->escape(JText::_( 'Manage menu for sites by selecting a template site in next page' )); ?>"
    src="templates/khepri/images/header/icon-48-module.png">
<span><?php echo JText::_( 'Spoke Menu Management' ); ?></span></a></div>
</div>
<?php $params = JComponentHelper::getParams('com_hub2');
$sitemanagerurl = $params->get('sitemanagerurl','');
if ($sitemanagerurl) {
?>
<div style="float: left;">
<div class="icon"><a href="<?php echo $sitemanagerurl?>" target="_blank"> <img
    alt="<?php echo $this->escape(JText::_( 'Create new Spoke' )); ?>"
    title="<?php echo $this->escape(JText::_( 'Create the Database and filesystem for a new Spoke' )); ?>"
    src="templates/khepri/images/header/icon-48-module.png">
<span><?php echo JText::_( 'Create new Spoke' ); ?></span></a></div>
</div>
<?php } ?>
<?php } ?>
</div>
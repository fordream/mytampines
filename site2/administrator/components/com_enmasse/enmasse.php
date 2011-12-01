<?php
/*------------------------------------------------------------------------
# En Masse - Social Buying Extension 2010
# ------------------------------------------------------------------------
# By Matamko.com
# Copyright (C) 2010 Matamko.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.matamko.com
# Technical Support:  Visit our forum at www.matamko.com
-------------------------------------------------------------------------*/

// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Require the base controller
 
require_once( JPATH_COMPONENT.DS.'controller.php' );

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
 
// Create the controller
$classname    = 'EnmasseController'.ucFirst($controller);
$controller   = new $classname( );
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
//$controller->execute( JRequest::getVar( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();

$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
?>
<style>
fieldset.adminform label, fieldset.adminform span.faux-label {
    min-width: 5px;
    padding: 0 5px 0 0;
}
fieldset label, fieldset span.faux-label {
    clear: none;
    display: block;
    float: left;
    margin: 5px 0;
}
</style>
<?php
}
?>
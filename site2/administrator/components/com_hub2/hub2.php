<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//set Globals
jimport( 'joomla.application.component.model' );

require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'hub2includepaths.php');

// Load message codes
require_once(dirname(__FILE__).DS.'helpers'.DS.'messagecode.php');

// Load User roles
require_once( JPATH_ROOT.DS.'components'.DS.'com_hub2'.DS.'userRole.php' );

require_once( JPATH_ROOT.DS.'components'.DS.'com_hub2'.DS.'hub2definition.php' );

// set up javascripts
$document = &JFactory::getDocument();
$document->addScript(JURI::root(true)."/components/com_hub2/js/min/jquery-1.6.2.min.js");
$document->addScriptDeclaration('jQuery.noConflict();');
$document->addStyleSheet(JURI::root(true)."/administrator/components/com_hub2/admin.css");

if (ISSITE && !IS_MANAGER) {
    // only dashboard available on a site
    JRequest::setVar('view','dashboard');
}
if (ISSITE && IS_MANAGER) {
    // only dashboard and site available on multisite manager
    $view = JRequest::getVar('view');
    if ($view !== 'dashboard' && $view !== 'sitemanager') {
        JRequest::setVar('view','dashboard');
    }
}

// Map view to controller
$_controllerMap = array(
        'author' => 'hyperlocalizer',
        'category' => 'hyperlocalizer',
        'site' => 'hyperlocalizer',
        'region' => 'hyperlocalizer',
        'postcode' => 'hyperlocalizer',
        'siteparams' => 'hyperlocalizer',
        'media' => 'media',
        'topic' => 'hyperlocalizer',
        'tag' => 'hyperlocalizer',
        'editorsitesrelations' => 'hyperlocalizer',
        'socialtokens' => 'hyperlocalizer',
        'sitemanager' => 'siteManager',
        'xmlapimanager'=> 'hyperlocalizer'
);

$view = JRequest::getVar('view','dashboard');
if(!array_key_exists($view, $_controllerMap)) {
    $view = 'dashboard';
}

// Create the controller
$controller = $_controllerMap[$view];
if ($controller == 'media') {
    $path = JPATH_SITE.DS.'components'.DS.'com_hub2'.
    DS.'controllers'.DS.$controller.'Controller.php';
} else {
    $path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.
    DS.'controllers'.DS.$controller.'Controller.php';
}
if (file_exists($path)) {
    require_once $path;
}else {
    if (ISSITE) {
        // Load Base Contorller Class
        require_once( dirname(__FILE__).DS.'controllers'.DS.'siteManagerController.php' );
        $controller = 'siteManager';
    } else {
        // Load Base Contorller Class
        require_once( dirname(__FILE__).DS.'controllers'.DS.'hyperlocalizerController.php' );
        $controller = 'hyperlocalizer';
    }
}

$classname    = 'Hub2Controller'.$controller;
$controller   = new $classname( );


// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();
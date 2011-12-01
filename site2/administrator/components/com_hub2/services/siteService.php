<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */

session_start();
// this has to be done before loading Joomla bootstrap since it is lost!
$_REQUEST['HTTP_RAW_POST_DATA'] = isset($GLOBALS['HTTP_RAW_POST_DATA'])
? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

//bootstrap
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$dir = dirname(__FILE__).DS.'..'.DS.'..'.DS.'..'.DS.'..';
define('JPATH_BASE', realpath($dir));
require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
require_once JPATH_BASE.DS.'includes'.DS.'framework.php';

JError::setErrorHandling( E_ERROR,   'ignore' );
JError::setErrorHandling( E_WARNING, 'ignore' );
JError::setErrorHandling( E_NOTICE,  'ignore' );


// create the mainframe object
$mainframe =& JFactory::getApplication('site');

// jxtended
require_once ( JPATH_PLUGINS . DS . 'system' . DS . 'jxtended.php');
// model paths
jimport( 'joomla.application.component.model' );
require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'hub2includepaths.php');

require_once( JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'userRole.php');

$config = &JFactory::getConfig();
if ($config->getValue('config.debug')) {
    ini_set("soap.wsdl_cache_enabled", "0");
    // disabling WSDL cache
    error_reporting(E_ALL);
}

ob_start("ob_gzhandler");

if(!extension_loaded("soap")) {
    die("Soap extension not loaded!");
}

/** Schrijft de gegeven tekst naar de debug file */
function debug($txt,$file="debug.txt") {
    $fp = fopen($file, "a");
    fwrite($fp, str_replace("\n","\r\n","\r\n".$txt));
    fclose($fp);
}

/** Schrijft het gegeven object weg in de debug log */
function debugObject($txt,$obj) {
    ob_start();
    print_r($obj);
    $data = ob_get_contents();
    ob_end_clean();
    debug($txt."\n".$data);
}

// include all classes
require_once("createWSDL.php");
require_once("siteServer.php");
require_once("soapResponse.php");
require_once ('mediasizestruct.php');

// soap configuration
$actor = "http://www.hyperlocalizer.com";
$structureMap = array("soapResponse","mediasizestruct");
$wsdlFolder = dirname(__FILE__).DS.'wsdl'.DS;
$classNames = array("siteServer");

$uri = JURI::getInstance();
$file = $uri->getHost().$uri->getPort().str_replace('/','_',$uri->base(true));
$wsdlfile = $wsdlFolder.$file.$_REQUEST['class'].".wsdl";
$soapURI = $actor;


if (isset($_SERVER['QUERY_STRING']) && strpos($_SERVER['QUERY_STRING'], 'wsdl') !== false) {
    $updated = !file_exists($wsdlfile);
    if (!$updated) {
        foreach ($structureMap as $structure) {
            if (filemtime($wsdlfile) < filemtime($structure.".php")) {
                $updated = true;
            }
        }
        foreach ($classNames as $class) {
            if (filemtime($wsdlfile) < filemtime($class.".php")) {
                $updated = true;
            }
        }
    }
    if(!$updated) {
        // error_log('reading existing');
        header("Content-type: text/xml");
        # equal or newer
        $fp = fopen($wsdlfile,"r");
        while (!flock($fp,LOCK_SH)) {

        }
        readfile($wsdlfile);
        flock($fp,LOCK_UN);
        fclose($fp);
    } else {
        //error_log('recreating');
        // create and echo output
        createWSDL($soapURI,$actor,$classNames,$structureMap,$wsdlFolder,true);
    }
} else {
    //error_log('handling server');
    if (!file_exists($wsdlfile)) {
        // create but do not echo output
        createWSDL($soapURI,$actor,$classNames,$structureMap,$wsdlFolder);
    }
    $options = Array('actor' => $actor, 'classmap' => $structureMap,
        'uri'=>$soapURI);

    header("Content-type: text/xml");
    # equal or newer
    $fp = fopen($wsdlfile,"r");
    while (!flock($fp,LOCK_SH)) {

    }
    $server = new SoapServer($wsdlfile, $options);
    flock($fp,LOCK_UN);
    fclose($fp);
    $server->setClass($_REQUEST['class']);
    $server->setPersistence(SOAP_PERSISTENCE_REQUEST);

    use_soap_error_handler(true);
    $server->handle();
}

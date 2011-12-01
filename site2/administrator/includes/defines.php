<?php
/**
* @version		$Id: defines.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//Global definitions
//Joomla framework path definitions
$parts = explode( DS, JPATH_BASE );
array_pop( $parts );

//Defines
define( 'JPATH_ROOT',			implode( DS, $parts ) );

define( 'JPATH_SITE',			JPATH_ROOT );
/** Hyperlocalizer modification */
// following function is from JMS WIN
   //------------ _getCurrentURL ---------------
   /**
    * This code is extracted from JURI::getInstancefunction.
    * The Query String is ignored
    *
    * @note this function must be IDENTICAL in the source
    * - /include/defined_multisites.php
    * - /include/multisites.php
    */
   function _getCurrentURL()
   {
		// Determine if the request was over SSL (HTTPS)
		if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
			$https = 's://';
		} else {
			$https = '://';
		}

		/*
		 * Since we are assigning the URI from the server variables, we first need
		 * to determine if we are running on apache or IIS.  If PHP_SELF and REQUEST_URI
		 * are present, we will assume we are running on apache.
		 */
		if (!empty ($_SERVER['PHP_SELF']) && !empty ($_SERVER['REQUEST_URI'])) {

			/*
			 * To build the entire URI we need to prepend the protocol, and the http host
			 * to the URI string.
			 */
			// If the HTTP_HOST is present in front of REQUEST_URI then ignore the HTTP_HOST
			// Otherwise, concatenate the HTTP_HOST and REQUEST_URI
			$host   = rtrim( 'http' . $https . $_SERVER['HTTP_HOST'], '/');
			$len = strlen( $host);
			if ( strlen( $_SERVER['REQUEST_URI']) == $len) {
			   if ( strtolower( $_SERVER['REQUEST_URI']) == strtolower( $host)) {
			   // Ignore the HTTP_HOST
      			$theURI =  $_SERVER['REQUEST_URI'];
			   }
			   else {
					$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			   }
			}
			else if ( strncmp( strtolower( $_SERVER['REQUEST_URI']), strtolower( $host) . '/', $len+1) == 0) {
			   // Ignore the HTTP_HOST
   			$theURI =  $_SERVER['REQUEST_URI'];
			}
			else {
				$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			}

		/*
		 * Since we do not have REQUEST_URI to work with, we will assume we are
		 * running on IIS and will therefore need to work some magic with the SCRIPT_NAME and
		 * QUERY_STRING environment variables.
		 */
		}
		 else
		 {
			// IIS uses the SCRIPT_NAME variable instead of a REQUEST_URI variable... thanks, MS
			$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
		}

		// Now we need to clean what we got since we can't trust the server var
		$theURI = urldecode($theURI);
		$theURI = str_replace('"', '&quot;',$theURI);
		$theURI = str_replace('<', '&lt;',$theURI);
		$theURI = str_replace('>', '&gt;',$theURI);
		$theURI = preg_replace('/eval\((.*)\)/', '', $theURI);
		$theURI = preg_replace('/[\\\"\\\'][\\s]*javascript:(.*)[\\\"\\\']/', '""', $theURI);

		$result = rtrim( strtolower( $theURI), '/');
		return $result;
   }

// following function is from JMS WIN
   //------------ _getHostInfo ---------------
   /**
    * @note this function must be IDENTICAL in the source
    * - /include/defined_multisites.php
    * - /include/multisites.php
    */
   function _getHostInfo( $URL)
   {
		$posBegin = strpos( $URL, '://');
		if ( $posBegin > 0) {
		   $posBegin += 3;
		   $posEnd = strpos( $URL, '/', $posBegin);
		   if ( $posEnd > 0) {
		      $host = substr( $URL, $posBegin, $posEnd-$posBegin);
		   }
		   else {
		      $host = substr( $URL, $posBegin);
		   }
		}
		// If http(s):// is missing in front of the URL,
		else {
		   $posEnd = strpos( $URL, '/');
		   if ( $posEnd > 0) {
		      $host = substr( $URL, 0, $posEnd);
		   }
		   else {
		      $host = $URL;
		   }
		   // Add a http in front of the URL
		   $URL = 'http://' . $URL;
		}

		// Compute the port
		// If a port in present in the host
		$posPort = strpos( $host, ':');
		if ( $posPort>0) {
		   $port = substr( $host, $posPort+1);    // Get the port
		   $host = substr( $host, 0, $posPort);   // remove the port from the host
		}
		else {
		   if ( substr( $URL, 0, 6) == 'https:') {
		      $port = 443;
		   }
		   else if ( substr( $URL, 0, 6) == 'http:') {
		      $port = 80;
		   }
		}


		// Build the results
		$results          = array();
		$results['URL']   = rtrim( strtolower( $URL), '/');                 // The Full url EXCLUDING the parameters
		$results['host']  = strtolower( $host);
		if (!empty( $port)) {
   		$results['port']  = strtolower( $port);
		}
      return $results;
   }

   $url = _getCurrentURL();
   $hostInfo = _getHostInfo(_getCurrentURL());
   $host = $hostInfo['host'];
   $host = preg_replace("/^www\./i",'',$host); // remove beginning www. if any

   // check file exists in the multisites folder
   if (file_exists(JPATH_ROOT.DS.'multisites'.DS.$host.DS.'configuration.php')) {
        define( 'JPATH_CONFIGURATION',  JPATH_ROOT.DS.'multisites'.DS.$host );
        define( 'JPATH_CACHE',          JPATH_CONFIGURATION.DS.'cache');
        define( 'IS_MANAGER',          false);
   } else {
        define( 'JPATH_CONFIGURATION',  JPATH_ROOT );
        define( 'JPATH_CACHE',          JPATH_BASE.DS.'cache');
        define( 'IS_MANAGER',          true);
   }

   // do not allow install on individual sites
   if ($_REQUEST['option'] == 'com_installer' && !IS_MANAGER) {
       echo "You cannot install new software directly to individual sites. This should be done via the Multisite Manager.";
       exit;

   }
   //echo JPATH_CONFIGURATION;
   //echo JPATH_CACHE;
   //exit;
/** end Hyperlocalizer modification */

/** HYPERLOCALIZER Changes - commented the line below*/
//define( 'JPATH_CONFIGURATION', 	JPATH_ROOT );
define( 'JPATH_ADMINISTRATOR', 	JPATH_ROOT.DS.'administrator' );
define( 'JPATH_XMLRPC', 		JPATH_ROOT.DS.'xmlrpc' );
define( 'JPATH_LIBRARIES',	 	JPATH_ROOT.DS.'libraries' );
define( 'JPATH_PLUGINS',		JPATH_ROOT.DS.'plugins'   );
define( 'JPATH_INSTALLATION',	JPATH_ROOT.DS.'installation' );
define( 'JPATH_THEMES',			JPATH_BASE.DS.'templates' );
/** HYPERLOCALIZER Changes - commented the line below*/
//define( 'JPATH_CACHE',			JPATH_BASE.DS.'cache' );
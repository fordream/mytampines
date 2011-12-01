<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * This class allows general installation of files related to plugin for AlphaUserPoints
 */
class aupInstaller {
	var $_iswin			= false;
	var $errno			= 0;
	var $error			= "";
	var $_unpackdir		= "";

	/** @var string The directory where the element is to be installed */
	var $_plugindir 		= '';
	var $_uploadfile		= null;
	var $_realname			= null;

	/**
	* Constructor
	*/
	function aupInstaller() {
		$this->_iswin = (substr(PHP_OS, 0, 3) == 'WIN');
		$this->_plugindir = JPath::clean( JPATH_COMPONENT_ADMINISTRATOR . DS .'assets'. DS .'plugins' );		
	}

	/**
	 * Installation of a single file or archive for the AlphaUserPoints files
	 * @param array uploadfile	retrieved information transferred by the upload form
	 */
	function install( $uploadfile = null ) {
		if( $uploadfile === null ) {
			return false;
		}
		$this->_uploadfile = $uploadfile['tmp_name'];
		$this->_realname = $uploadfile['name'];

		return $this->upload();
	}

	/**
	* Uploads and unpacks a file
	* @return boolean True on success, False on error
	*/
	function upload() {
		if( !eregi( '.xml$', $this->_realname ) ) {
			if(! $this->extractArchive() ) {
				$this->error = JText::_('AUP_EXTRACT_ERROR');				
				JError::raiseWarning(0, $this->error );
				return false;
			}
		}

		if( !is_array( $this->_uploadfile ) ) {
			if(! @copy($this->_uploadfile, $this->_plugindir . DS . $this->_realname) ) {
				$this->errno = 2;
				$this->error = JText::_('AUP_FILEUPLOAD_ERROR');				
				JError::raiseWarning(0, $this->error );
				return false;
			} else {
				$file = $this->_realname ;
			}
		} else {
			$file = array();
			$i = 0;
			foreach ( $this->_uploadfile as $_file ) {
				if(! @copy($this->_unpackdir . DS . $_file, $this->_plugindir . DS . $_file) ) {
					$this->errno = 2;
					$this->error = JText::_('AUP_FILEUPLOAD_ERROR');
					JError::raiseWarning(0, $this->error );
					return false;
				}
				$file[$i] = $_file;
				$i++;
			}
		}
		return $file;
	}

	/**
	* Extracts the package archive file
	* @return boolean True on success, False on error
	*/
	function extractArchive() {

		$base_Dir 		= JPath::clean( JPATH_ROOT . DS . 'media' . DS );		

		$archivename 	= $base_Dir . $this->_realname;
		$tmpdir 		= uniqid( 'install_' );

		$extractdir 	=JPath::clean( $base_Dir . $tmpdir );
		$archivename 	=JPath::clean( $archivename, false );
		$this->_unpackdir = $extractdir;

		if (eregi( '.zip$', $archivename )) {
			// Extract functions
			require_once( JPATH_ADMINISTRATOR . '/includes/pcl/pclzip.lib.php' );
			require_once(  JPATH_ADMINISTRATOR. '/includes/pcl/pclerror.lib.php' );
			$zipfile = new PclZip( $this->_uploadfile );
			if($this->_iswin) {
				define('OS_WINDOWS',1);
			} else {
				define('OS_WINDOWS',0);
			}

			$ret = $zipfile->extract( PCLZIP_OPT_PATH, $extractdir );
			if($ret == 0) {
				$this->errno = 1;
				$this->error = 'Unrecoverable error "'.$zipfile->errorName(true).'"';
				JError::raiseWarning(0, $this->error );
				return false;
			}
		} else {
			require_once( JPATH_SITE . '/includes/Archive/Tar.php' );
			$archive = new Archive_Tar( $this->_uploadfile );
			$archive->setErrorHandling( PEAR_ERROR_PRINT );

			if (!$archive->extractModify( $extractdir, '' )) {
				$this->setError( 1, JText::_('AUP_EXTRACT_ERROR') );
				JError::raiseWarning(0, $this->setError );
				return false;
			}
		}

		// Try to find the correct install dir. in case that the package have subdirs
		// Save the install dir for later cleanup
		jimport('joomla.filesystem.folder');
		$this->_uploadfile =JFolder::files( $extractdir, '' );

		if (count( $this->_uploadfile ) == 1) {
			if (is_dir( $extractdir . $this->_uploadfile[0] )) {
				$this->_unpackdir  = JPath::clean( $extractdir . $this->_uploadfile[0] );
				$this->_uploadfile = JFolder::files( $extractdir, '' );
			}
		}

		return true;
	}
}
?>
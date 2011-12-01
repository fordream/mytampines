<?php
/**
 * XDrives.php
 * 
 * Example PHP usage of X-Drives
 * 
 * PHP Version 5
 * 
 * Copyright © Microsoft Corporation. All Rights Reserved.
 * This code released under the terms of the
 * Microsoft Public License (MS-PL, http://opensource.org/licenses/ms-pl.html.)
 * 
 * @category  Microsoft
 * @package   WindowsAzureCmdLineTools4PHP
 * @author    Jeff Tanner <a-jetann@microsoft.com>
 * @copyright 2010 Copyright © Microsoft Corporation. All Rights Reserved
 * @license   Microsoft Public License, MS-PL, http://opensource.org/licenses/ms-pl.html
 * @link      http://www.microsoft.com
 */


function XDrivesCustomError($errno, $errstr)
  {
  echo "<b>XDrives Error:</b> [$errno] $errstr";
  }

//set error handler
set_error_handler("XDrivesCustomError");

$x_drives = new XDrives();

$x_drives->main();

/**
 * Example of using X-Drives
 *
 * @category  Microsoft
 * @package   WindowsAzureCmdLineTools4PHP
 * @author    Jeff Tanner <a-jetann@microsoft.com>
 * @copyright 2010 Copyright © Microsoft Corporation. All Rights Reserved
 * @license   Microsoft Public License, MS-PL, http://opensource.org/licenses/ms-pl.html
 * @link      http://www.microsoft.com
 */
class XDrives
{
	private $_aRandomLatin
		= array(
			"Lorem ipsum dolor sit amet, consectetur adipiscing elit",
			"In vulputate rhoncus est, ac aliquam tellus lacinia vel",
			"Pellentesque et purus quis erat laoreet vehicula sed ut orci",
			"Phasellus dignissim metus id arcu ultricies commodo",
			"Morbi eu quam nisi, id sollicitudin libero",
			"Curabitur ultrices lacinia ligula, et porttitor nunc placerat et",
			"Fusce venenatis fermentum nulla, nec faucibus lorem luctus id",
			"Donec sed tellus eget odio posuere ultricies",
			"Proin cursus ante ac urna ornare nec consequat nisl viverra",
			"Proin nec massa sapien, id placerat urna",
			"Etiam a neque nisl, ut tincidunt mi",
			"Suspendisse rutrum nisi at leo pulvinar luctus",
			"Cras tristique tortor vel arcu ornare id egestas quam vulputate",
			"Vestibulum tincidunt dui vitae quam iaculis quis tempor ligula vestibulum",
			"Vivamus tincidunt nulla ut tortor aliquam tristique lobortis nisl elementum",
			"Vestibulum venenatis rutrum odio, id auctor leo pharetra et",
			"Ut in ligula et mi fringilla interdum",
			"Praesent laoreet sem vel lacus laoreet quis pharetra quam malesuada",
			"Sed commodo interdum massa, non porta orci convallis et",
			"Maecenas rutrum sapien quis arcu pretium vel molestie dui auctor",
			"Cras mollis hendrerit orci, egestas ultrices sapien pretium a",
			"Vestibulum eget sapien vitae neque rutrum tincidunt",
			"Sed sit amet turpis libero, ac facilisis orci",
			"In in leo vitae lacus sagittis hendrerit"
	
		);
		
    /**
     * Main entry point to utilize X-Drives
     * 
     * @param string  $strDir         Path of directory to create
     * 
     * @return void
     */
	public function main()
	{
		try {
			/*
			 * Read X-Drives Environment Variable
			 * of successfully mounted drives.
			 */
			$sXDrives=@getenv("X_DRIVES"); 
			
			if ( is_null($sXDrives) || !is_string($sXDrives) || empty($sXDrives)) {
				printf("<h2>X_DRIVES Not Defined</h2>");
				return;
			}
			
			printf("<h2>X_DRIVES = \"%s\"</h2>", $sXDrives);
			
			/*
			 * Contents of X_DRIVES is laid out as follows:
			 * xdrive_label=[drive_letter,drive_access_mode];...
			 * 
			 * Example:
			 * MyDriveB=[a,w];MyDriveC=[b,r]
			 * 
			 */			
			$aXDrives = explode(";", $sXDrives);
			
			foreach( $aXDrives as $sXDrive ) {
				$aXDrive = explode("=", $sXDrive);
				
				$sXDriveLabel = $aXDrive[0];
				$sXDriveAttribs = $aXDrive[1];
				
				$sXDriveAttribs = trim($sXDriveAttribs, "[]");
				$aXDriveAttribs = explode(",", $sXDriveAttribs);
				$sXDriveLetter = $aXDriveAttribs[0];
				$sXDriveAccess = $aXDriveAttribs[1];
				$bReadOnly = strcmp($sXDriveAccess, "r") == 0;
				
				printf(
					"<br><hr><br><p><b>X-Drive: %s, %s%s</b></p>", 
					$sXDriveLabel, 
					$sXDriveLetter, 
					$bReadOnly ? ", ReadOnly" : ""
				);
				
				$sDirPath = sprintf("%s:\\", $sXDriveLetter);
					
				$bIsDir = is_dir($sDirPath);
				printf("<p>%s exists = %s</p>", $sDirPath, $bIsDir ? "Yes" : "No");
				if ( !$bIsDir ) {
					continue;
				}
				
				$bIsWritable = is_writable($sDirPath);
				printf("<p>%s writable = %s</p>", $sDirPath, $bIsWritable ? "Yes" : "No");
				
				if ( !$bReadOnly ) {
					$this->_write_files($sDirPath);
				}
				$this->_read_files($sDirPath);
			}
		} catch (Exception $ex) {
			printf("Exception: " . __METHOD__ . ": {$ex->getMessage()}\n");
			exit(__METHOD__ . " failed");
		}
	}
	
    /**
     * List files within an X-Drive
     * 
     * @param string $sDirPath Path to X-Drive
     * 
     * @return void
     */
	private function _list_directory($sDirPath)
	{
		try {
		    // create a handler for the directory
			if(!$dh = @opendir($sDirPath)) continue;
		    // keep going until all files in directory have been read
		    while ($sFile = readdir($dh)) {
		        // if $file isn't this directory or its parent, 
		        // add it to the results array
		        if ($sFile != '.' && $sFile != '..') {
		            printf("<p>%s</p>", $sFile);
		        }
		    }
		    closedir($dh);	
		} catch (Exception $ex) {
			printf("Exception: " . __METHOD__ . ": {$ex->getMessage()}\n");
			exit(__METHOD__ . " failed");
		}
	}
	
    /**
     * Remove all files within an X-Drive
     * 
     * @param string $sDirPath Path to X-Drive
     * 
     * @return void
     */	
	private function _empty_directory($sDirPath)
	{
		try {
		    // create a handler for the directory		
			if(!$dh = @opendir($sDirPath)) continue;
		    while (false !== ($obj = readdir($dh))) {
		        if($obj=='.' || $obj=='..') continue;
		        @unlink($sDirPath.$obj);
		    }
		    closedir($dh);
		} catch (Exception $ex) {
			printf("Exception: " . __METHOD__ . ": {$ex->getMessage()}\n");
			exit(__METHOD__ . " failed");
		}
	}
	
    /**
     * Create or Write files into an X-Drive
     * 
     * @param string $sDirPath Path to X-Drive
     * 
     * @return void
     */		
	private function _write_files($sDirPath)
	{
		try {
			$this->_empty_directory($sDirPath);
			$sXDriveAccess = "a+";
			
			for( $i = 1; $i <= 10; $i++) {
				$contents = "";
				$sFilePath = sprintf("%stest_%d.txt", $sDirPath, $i);
				
				if ( !($fh = fopen($sFilePath, $sXDriveAccess)) ) {
					printf("<p><b>Failed fopen:</b> \"%s\"</p>", $sFilePath);
					continue;
				}
				
				$sValue = $this->_aRandomLatin[rand(0, count($this->_aRandomLatin) - 1)];
				$mixResult = fwrite($fh, $sValue);
				fclose($fh);
				
				$result = $mixResult === false ? "Failed" : $mixResult;
				$iFileSize = filesize($sFilePath);	
				printf("<p>%s: size=%d, access=%s, result=%s</p>", $sFilePath, $iFileSize, $sXDriveAccess, "{$result}");		
			}
		} catch (Exception $ex) {
			printf("Exception: " . __METHOD__ . ": {$ex->getMessage()}\n");
			exit(__METHOD__ . " failed");
		}
	}
	
    /**
     * Read files in an X-Drive
     * 
     * @param string $sDirPath Path to X-Drive
     * 
     * @return void
     */		
	private function _read_files($sDirPath)
	{
		try {
			$sXDriveAccess = "r";
				
		    // create a handler for the directory
			if(!$dh = @opendir($sDirPath)) continue;
		    // keep going until all files in directory have been read
		    while ($sFile = readdir($dh)) {
		        // if $file isn't this directory or its parent, 
		        // add it to the results array
		        if ($sFile == '.' || $sFile == '..') {
		        	continue;
		        }
		        
		        $sFilePath = sprintf("%s%s", $sDirPath, $sFile);
				$iFileSize = filesize($sFilePath);
				printf("<p>%s: size=%d, access=%s</p>", $sFilePath, $iFileSize, $sXDriveAccess);
				
				if ( !($fh = fopen($sFilePath, $sXDriveAccess)) ) {
					printf("<p><b>Failed fopen:</b> \"%s\"</p>", $sFilePath);
					continue;
				}
				
				$mixResult = fread($fh, $iFileSize);
				fclose($fh);
				
				$result = $mixResult === false ? "Failed" : $mixResult;
				printf("<p><b>Contents:</b> %s</p>", "{$result}");
		    }
		    closedir($dh);	
		} catch (Exception $ex) {
			printf("Exception: " . __METHOD__ . ": {$ex->getMessage()}\n");
			exit(__METHOD__ . " failed");
		}
	}
}
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

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'helpers'.DS.'media.php');
require_once( JPATH_ADMINISTRATOR . DS ."components". DS ."com_enmasse". DS ."helpers". DS ."EnmasseHelper.class.php");
/// load language
$language =& JFactory::getLanguage();
$base_dir = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_enmasse';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) == '1.6'){
    $extension = 'com_enmasse16';
}else{
    $extension = 'com_enmasse';
}
if($language->load($extension, $base_dir, $language->getTag(), true) == false)
{
	 $language->load($extension, $base_dir, 'en-GB', true);
}


class EnmasseControllerUploader extends JController
{

	function display()
	{
		JRequest::setVar('view', 'uploader');
		JRequest::setVar('layout', 'show');
		parent::display();
	}
	function getExtension($str) {

         $i = strrpos($str,".");
         if (!$i) { return ""; } 
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 	}
	
    function upload()
	{
		global $mainframe;
        
        $version = new JVersion;
        $joomla = $version->getShortVersion();
        if(substr($joomla,0,3) == '1.6'){
    	   $mainframe = JFactory::getApplication();
        }
        
		$fileArr    = JRequest::getVar( 'Filedata', '', 'files', 'array' );
		$folder		= JRequest::getVar( 'folder', '', '', 'path' );
		$format		= JRequest::getVar( 'format', 'html', '', 'cmd');
		$return		= JRequest::getVar( 'return-url', null, 'post', 'base64' );
		$parentId   = JRequest::getVar('parentId');
		$parent     = JRequest::getVar('parent');
		$couponbg   = JRequest::getVar('couponbg');
		$err		= null;
		
		//------------------------------
		// to get the image size from seeting table
		
		$dealImageSize = EnmasseHelper::getDealImageSize();
		
		if(!empty($dealImageSize) && $dealImageSize->image_height!=0 && $dealImageSize->image_width!=0)
		{
			$image_height = $dealImageSize->image_height;
			$image_width = $dealImageSize->image_width;
		}
		else
		{
			$image_height = 252 ;
			$image_width = 400;
		}
		
		if($couponbg!='' && $couponbg!=null)
		{
			$image_height = 424 ;
			$image_width = 680;
		}
		
		for($i=0 ; $i<count($fileArr['name']); $i++)
		{
			$file[$i]['name'] = $fileArr['name'][$i];
			$file[$i]['type'] = $fileArr['type'][$i];
			$file[$i]['tmp_name'] = $fileArr['tmp_name'][$i];
			$file[$i]['error'] = $fileArr['error'][$i];
			$file[$i]['size'] = $fileArr['size'][$i];
		}
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		jimport('joomla.filesystem.file');
		$random = rand();
		for ($count=0 ; $count < count($file); $count++)
		{
			
			$file[$count]['name']	= JFile::makeSafe($file[$count]['name']);
	        
			if (isset($file[$count]['name'])) 
			{
				// Modifine by phuocndt - update image upload code
				$filepath = JPath::clean(JPATH_SITE.DS.'components'.DS.'com_enmasse'.DS.'upload'.DS.strtolower($random.$file[$count]['name']));
	            $imagepath = JPath::clean('components'.DS.'com_enmasse'.DS.'upload'.DS.strtolower($random.$file[$count]['name']));
                //$imagepath = $random.$file[$count]['name'];
                $imagePathArr[$count] = $imagepath;
	            if (!MediaHelper::canUpload( $file[$count], $err )) 
	            {
					if ($format == 'json') 
					{
						jimport('joomla.error.log');
						$log = &JLog::getInstance('upload.error.php');
						$log->addEntry(array('comment' => 'Invalid: '.$filepath.': '.$err));
						header('HTTP/1.0 415 Unsupported Media Type');
						jexit('Error. Unsupported Media Type!');
					}
					else
					 {
						JError::raiseNotice(100, JText::_($err));
						// REDIRECT
						if ($return)
						{
							$mainframe->redirect(base64_decode($return).'&folder='.$folder);
						}
						return;
					}
				}
	
				if (JFile::exists($filepath)) 
				{
					if ($format == 'json') 
					{
						jimport('joomla.error.log');
						$log = &JLog::getInstance('upload.error.php');
						$log->addEntry(array('comment' => 'File already exists: '.$filepath));
						header('HTTP/1.0 409 Conflict');
						jexit('Error. File already exists');
					} 
					else 
					{
						JError::raiseNotice(100, JText::_('Error. File already exists'));
						// REDIRECT
						if ($return) 
						{
							$mainframe->redirect(base64_decode($return).'&folder='.$folder);
						}
						return;
					}
				}
				
				    $image =$file[$count]["name"];
 					$uploadedfile = $file[$count]['tmp_name'];
	                $filename = stripslashes($file[$count]['name']);
  		            $extension =$this->getExtension($filename);
 		            $extension = strtolower($extension);
 		            $size=filesize($file[$count]['tmp_name']);
 		            
					if($extension=="jpg" || $extension=="jpeg" )
					{
					$uploadedfile = $file[$count]['tmp_name'];
					$src = imagecreatefromjpeg($uploadedfile);
					}
					else if($extension=="png")
					{
					$uploadedfile = $file[$count]['tmp_name'];
					$src = imagecreatefrompng($uploadedfile);
					
					}
					
					list($width,$height)=getimagesize($uploadedfile);
					$newwidth=60;
					$newheight=($height/$width)*$newwidth;
					$tmp=imagecreatetruecolor($newwidth,$newheight);
		
		            if($parent == 'merchant')
		            {
		            	$newwidth1=$width;
						$newheight1=$height;
		            }
		            else
		            {
						$newwidth1=$image_width;
						$newheight1=$image_height;
		            }
					$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
					
					imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
					
					imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
					$filename = $filepath;
			
					$filename1 = $filepath;
		
		
		
					imagejpeg($tmp,$filename,100);
					
					imagejpeg($tmp1,$filename1,100);
					
					imagedestroy($src);
					imagedestroy($tmp);
					imagedestroy($tmp1);
					
			        if ($count == count($file)-1) 
				    {
                       
                            $mainframe->redirect(base64_decode($return).'&folder='.urlencode(serialize($imagePathArr)).'&parentId='.$parentId);
                            
                      
					}
			
			} 
			else 
			{
				$mainframe->redirect('index.php', 'Invalid Request', 'error');
			}
		}
		
		//$mainframe->redirect(base64_decode($return).'&folder='.$imagepath.'&parentId='.$parentId);
	}
	

}
?>
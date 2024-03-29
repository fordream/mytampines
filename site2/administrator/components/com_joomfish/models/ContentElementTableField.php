<?php
/**
 * Joom!Fish - Multi Lingual extention and translation manager for Joomla!
 * Copyright (C) 2003-2009 Think Network GmbH, Munich
 *
 * All rights reserved.  The Joom!Fish project is a set of extentions for
 * the content management system Joomla!. It enables Joomla!
 * to manage multi lingual sites especially in all dynamic information
 * which are stored in the database.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * $Id: ContentElementTableField.php 1391 2009-08-10 12:40:55Z geraint $
 * @package joomfish
 * @subpackage Models
 *
*/

// Don't allow direct linking
defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Description of a table field
 *
 * @package joomfish
 * @subpackage administrator
 * @copyright 2003-2009 Think Network GmbH
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version $Revision: 1296 $
 * @author Alex Kempkens <joomfish@thinknetwork.com>
 */
class ContentElementTablefield {
	var $Type='';
	var $Name='';
	var $Lable='';
	var $Translate=false;
	var $Option='';
	var $Length=30;
	var $MaxLength=80;
	var $Rows=15;
	var $Columns=30;
	var $posthandler="";
	var $prehandler="";
	var $prehandleroriginal="";
	var $prehandlertranslation="";
	
	// Can be boolean or array, if boolean defines if the buttons are displayed, if array defines a list of buttons not to show.
	var $ebuttons=true;

	// boolean to determine where to show this field if original is not blank e.g. content in modules
	var $ignoreifblank=0;
	
	/** originalValue value of the corresponding content table */
	var $originalValue;

	/** translationContent reference to the actual translation db object */
	var $translationContent;

	/** changed Flag that says if a field is changed or not */
	var $changed=false;

	/** this Flag explains if the original is empty or not */
	var $originalEmpty=false;

	/** Standard constructur
	*/
	function ContentElementTablefield( $tablefieldElement ) {
		$this->Type = trim( $tablefieldElement->getAttribute( 'type' ) );
		$this->Name = trim( $tablefieldElement->getAttribute( 'name' ) );
		$this->Lable = trim( $tablefieldElement->getText() );
		$this->Translate = trim( $tablefieldElement->getAttribute( 'translate' ) );
		$this->Option = trim( $tablefieldElement->getAttribute( 'option' ) );
		$this->Length = intval( $tablefieldElement->getAttribute( 'length' ) );
		$this->MaxLength = intval( $tablefieldElement->getAttribute( 'maxlength' ) );
		$this->Rows = intval( $tablefieldElement->getAttribute( 'rows' ) );
		$this->Columns = intval( $tablefieldElement->getAttribute( 'columns' ) );
		$this->posthandler = trim( $tablefieldElement->getAttribute( 'posthandler' ) );
		$this->prehandler = trim( $tablefieldElement->getAttribute( 'prehandler' ) );
		$this->prehandlertranslation = trim( $tablefieldElement->getAttribute( 'prehandlertranslation' ) );
		$this->prehandleroriginal = trim( $tablefieldElement->getAttribute( 'prehandleroriginal' ) );
		$this->ignoreifblank = intval( $tablefieldElement->getAttribute( 'ignoreifblank' ) );
		
		$this->ebuttons = trim( $tablefieldElement->getAttribute( 'ebuttons' ) );
		if (strpos($this->ebuttons,",")>0){
			$this->ebuttons = explode(",",$this->ebuttons);
		}
		else if ($this->ebuttons=="1"  || strtolower($this->ebuttons)=="true"){
			$this->ebuttons = true;
		}
		else if (strlen($this->ebuttons)==0) {
			$this->ebuttons = array("readmore");
		}
		else if ($this->ebuttons=="0"  || strtolower($this->ebuttons)=="false"){
			$this->ebuttons = false;
		}
		else if (strlen($this->ebuttons)>0){
			$this->ebuttons = array($this->ebuttons);
		}
	}
	
	function preHandle($element){
		if ($this->prehandler!="" && method_exists($this,$this->prehandler)){
			$prehandler=$this->prehandler;
			$this->$prehandler($element);
		}
	}
	function checkUrlType($element){
		if ($element->IndexedFields["type"]->originalValue=="url") $this->Type="text";
	}
}

?>

<?php
/**
* @version		1.1
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
* @copyright	Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
* 
* 
* DJ Classifieds is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Classifieds is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
* 
*/
class OptionList{
var $text;
var $value;
var $disabled;	

function __construct(){
$text=null;
$value=null;			
$disabled=null;
}
	
}


class TreeNodeHelper{
var $parent_id;
var $id;
var $name;
var $childs = Array();
var $level;

function __construct(){
$parent_id=null;
$id=null;
$name=null;
$childs[]=null;
$elem[]=null;
$level=0;
}


public function getSortList(& $lists,& $lists_const,& $option=Array(),& $sprawdzone=';'){

	$liczba = count($lists_const);
	foreach($lists as $list){
		if(!isset($list->level)){
			$list->level=0;
		}
			
			if(strstr($sprawdzone,';'.$list->id.';')){
				$flaga=1;
			}else{
				$flaga=0;
			}
			
			if($flaga==0){
			$sprawdzone.=$list->id.';';
			
			$this->parent_id = $list->parent_id;
			$this->id = $list->id;
			$this->name = $list->name;
			$op= new OptionList;
			$op->text=$this->name;
			$op->value=$this->id;
			$option[]=$op;
			
				$this->childs=Array();					
				   for($i=0; $i<$liczba;$i++ ){
					if($lists_const[$i]->parent_id==$list->id){		
						$child=new TreeNodeHelper();
						$child->id=$lists_const[$i]->id;
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->level=$list->level+1;
						$new_name=$lists_const[$i]->name;
							for($lev=0;$lev<$child->level;$lev++){
								$new_name="&nbsp;&nbsp;&nbsp;".$new_name;
							}
						$child->name=$new_name;

						
						$this->childs[]=$child;
						$this->getSortList($this->childs,$lists_const,$option,$sprawdzone);
					
					}
				}	
				
			}
	}
	return($option);		
}



}

?>


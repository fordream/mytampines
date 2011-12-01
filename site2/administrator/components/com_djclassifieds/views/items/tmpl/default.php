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
defined ('_JEXEC') or die('Restricted access');
require_once(dirname(__FILE__).DS.'helper.php');
$document=& JFactory::getDocument();
$lista=new DjclassifiedsModelCategories();
$sort_list=$lista->getCategories();
$cs = JURI::base().'components/com_djclassifieds/views/items/tmpl/style.css';

$document->addStyleSheet($cs);
/** Hyperlocalizer */
$jApp = &JFactory::getApplication();
$cid = JRequest::getVar('cid',0,'','int');
$cat_id = JRequest::getVar('cat_id',0,'','int');

$limit = JRequest::getVar('limit', 25, '', 'int');
$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
$ord_t = JRequest::getVar('ord_t', 'desc');
$order = JRequest::getVar('order');
if($ord_t=="desc"){
	$ord_t='asc';
}else{
	$ord_t='desc';
}
//Amol
require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'helpers'.DS.'hub2url.php');
$hubURL = Hub2URLHelper::getHub2ExternalURL();
$siteId = $this->hub2Details['id'];
?>


<form action="index.php" method="get" name="adminForm">
<table style="float: left; margin-right: 20px;"><div id="mod_dj_classifieds">
<ul class="djclassifieds" style="float: left; margin-right: 20px; margin-left:0px;">
	<?php
	//$_list = new TreeNodeHelper();
//$_list->getTreeList($sort_list); ?>
</ul></div>

</table>
<table width="100%">
	<tr>
      	<td align="left" width="50%">
      		<input type="text" type="text" name="find_name" size="30" maxlength="250" value="<?php echo JRequest::getVar('find_name');?>"/>
			<input type="submit" class="button" value="<?php echo JText::_( 'search' ); ?>" />
        </td>
		<td align="right" width="45%" style="padding-right:30px;">
			<?php
			$filter_category		= $jApp->getUserStateFromRequest( 'cat_id','cat_id',0,'int' );
			$_list = new TreeNodeHelper();
			$options = $_list->getSortList($sort_list,$sort_list);
			$javascript 	= 'onchange="document.adminForm.submit();"';
			echo JHTML::_('select.genericlist', $options, 'cat_id', $javascript, 'value', 'text', $filter_category);
			$filter_type = $jApp->getUserStateFromRequest( 'type','type',0,'int' );
	?>
		</td>
	</tr>
</table>
    <table class="adminlist" style="width: 100%;">
        <thead>
            <tr>
                <th width="5%">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->list); ?>);"/>
                </th>
                <th width="5%">
                    <a href="index.php?option=com_djclassifieds&task=items&order=id&cat_id=<?php echo $cat_id ; ?>&limit=<?php echo $limit; ?>&limitstart=<?php echo $limitstart;?>&ord_t=<?php echo $ord_t; ?>"><?php echo JText::_( 'id' ); ?></a>
					<?php
					if($order == 'id'){
						if(JRequest::getVar('ord_t')=="desc"){
							echo '<img src="'.JURI::base().'/images/sort_desc.png" />';
						}else{
							echo '<img src="'.JURI::base().'/images/sort_asc.png" />';
						}
					}
					?>
                </th>
                <th width="15%">
                    <a href="index.php?option=com_djclassifieds&task=items&order=name&cat_id=<?php echo $cat_id ; ?>&limit=<?php echo $limit; ?>&limitstart=<?php echo $limitstart;?>&ord_t=<?php echo $ord_t; ?>"><?php echo JText::_( 'NAME' ); ?></a>
					<?php
					if($order == 'name'){
						if(JRequest::getVar('ord_t')=="desc"){
							echo '<img src="'.JURI::base().'/images/sort_desc.png" />';
						}else{
							echo '<img src="'.JURI::base().'/images/sort_asc.png" />';
						}
					}
					?>
                </th>
                <th width="15%">
                    <a href="index.php?option=com_djclassifieds&task=items&order=category&cat_id=<?php echo $cat_id ; ?>&limit=<?php echo $limit; ?>&limitstart=<?php echo $limitstart;?>&ord_t=<?php echo $ord_t; ?>"><?php echo JText::_( 'CATEGORY' ); ?></a>
					<?php
					if($order == 'category'){
						if(JRequest::getVar('ord_t')=="desc"){
							echo '<img src="'.JURI::base().'/images/sort_desc.png" />';
						}else{
							echo '<img src="'.JURI::base().'/images/sort_asc.png" />';
						}
					}
					?>
				</th>
				<th width="20%">
                    <a href="index.php?option=com_djclassifieds&task=items&order=intro_desc&cat_id=<?php echo $cat_id ; ?>&limit=<?php echo $limit; ?>&limitstart=<?php echo $limitstart;?>&ord_t=<?php echo $ord_t; ?>"><?php echo JText::_( 'Introtext' ); ?></a>
               	<?php
					if($order == 'intro_desc'){
						if(JRequest::getVar('ord_t')=="desc"){
							echo '<img src="'.JURI::base().'/images/sort_desc.png" />';
						}else{
							echo '<img src="'.JURI::base().'/images/sort_asc.png" />';
						}
					}
					?>
				</th>
				<th width="10%">
                    <a href="index.php?option=com_djclassifieds&task=items&order=u_name&cat_id=<?php echo $cat_id ; ?>&limit=<?php echo $limit; ?>&limitstart=<?php echo $limitstart;?>&ord_t=<?php echo $ord_t; ?>"><?php echo JText::_( 'Username' ); ?></a>
               	<?php
					if($order == 'u_name'){
						if(JRequest::getVar('ord_t')=="u_name"){
							echo '<img src="'.JURI::base().'/images/sort_desc.png" />';
						}else{
							echo '<img src="'.JURI::base().'/images/sort_asc.png" />';
						}
					}
					?>
				</th>
                <th width="5%">
                    <a href="index.php?option=com_djclassifieds&task=items&order=payed&cat_id=<?php echo $cat_id ; ?>&limit=<?php echo $limit; ?>&limitstart=<?php echo $limitstart;?>&ord_t=<?php echo $ord_t; ?>"><?php echo JText::_( 'Paid' ); ?></a>
                	<?php
					if($order == 'payed'){
						if(JRequest::getVar('ord_t')=="desc"){
							echo '<img src="'.JURI::base().'/images/sort_desc.png" />';
						}else{
							echo '<img src="'.JURI::base().'/images/sort_asc.png" />';
						}
					}
					?>
				</th>
				<th width="5%">
                    <a href="index.php?option=com_djclassifieds&task=items&order=first&cat_id=<?php echo $cat_id ; ?>&limit=<?php echo $limit; ?>&limitstart=<?php echo $limitstart;?>&ord_t=<?php echo $ord_t; ?>"><?php echo JText::_( 'Promotion' ); ?></a>
					<?php
					if($order == 'first'){
						if(JRequest::getVar('ord_t')=="desc"){
							echo '<img src="'.JURI::base().'/images/sort_desc.png" />';
						}else{
							echo '<img src="'.JURI::base().'/images/sort_asc.png" />';
						}
					}
					?>
				</th>
                <th width="5%">
                    <a href="index.php?option=com_djclassifieds&task=items&order=published&cat_id=<?php echo $cat_id ; ?>&limit=<?php echo $limit; ?>&limitstart=<?php echo $limitstart;?>&ord_t=<?php echo $ord_t; ?>"><?php echo JText::_( 'PUBLISHED' ); ?></a>
                	<?php
					if($order == 'published'){
						if(JRequest::getVar('ord_t')=="desc"){
							echo '<img src="'.JURI::base().'/images/sort_desc.png" />';
						}else{
							echo '<img src="'.JURI::base().'/images/sort_asc.png" />';
						}
					}
					?>
				</th>


            </tr>
        </thead>
        <?php $i=0;

for ($i=0, $n=count( $this->list ); $i < $n; $i++) {

	$l = &$this->list[$i];

	//$l->name = JHTML::link(', $l->name);

	$checked = JHTML::_('grid.id', $i, $l->id );


	$published=JHTML::_('grid.published', $l, $i);

	?>
        <tr>
            <td>
                <?php echo $checked ?>
            </td>
            <td>
                <?php echo $l->id; ?>
            </td>
            <td>
                <?php

				if($image = $l->image_url){
					$images=explode(';', substr($image,0,-1));
				 	$pat = str_replace('/administrator','',JURI::base());
					$pat .= '/components/com_djclassifieds/images/'.$images[0].'.ths.jpg';

                                        //Amol
					$pat = $hubURL.'/components/com_djclassifieds/images/'.$images[0].'.ths.jpg';
					echo '<a href="index.php?option=com_djclassifieds&task=editItem&cid[]='.$l->id.'"><img src="'.$pat.'" /> '.$l->name.'</a>';
				}else{
					$pat = str_replace('/administrator','',JURI::base());
					$pat .= '/components/com_djclassifieds/images/no-image.png';

					//Amol
					$pat = $hubURL.'/components/com_djclassifieds/images/no-image.png';
					echo '<a href="index.php?option=com_djclassifieds&task=editItem&cid[]='.$l->id.'"><img src="'.$pat.'" /> '.$l->name.'</a>';
				}



				?>
            </td>
            <td>
				<?php
				if($l->cat_name){
					echo $l->cat_name;
				}else{
					echo '---';
				}
				?>
            </td>
			<td class="intro_desc">
                <?php
					if(strlen($l->intro_desc) > 75){
					   echo mb_substr($l->intro_desc, 0, 75,'utf-8').' ...';
					}else{
						echo $l->intro_desc;
					}
				?>
            </td>
			<td>
				<?php
				if($l->u_name){
					echo $l->u_name;
				}else{
					echo '---';
				}
				?>
			</td>
			<td align="center">
				<?php
				  if($l->payed=='0'){
				  	echo JText::_( 'No' );
				  }else{
				  	echo JText::_( 'Yes' );
				  }
				?>
			</td>
			<td align="center">
			<?php if($l->special=='0'){
					echo '<a href="index.php?option=com_djclassifieds&task=special&cat_id='.$cat_id.'&cid[]='.$l->id.'" class="img_checkt_r" ><img src="'.JURI::base().'/images/publish_x.png" ></a>';
				} else{
					echo '<a href="index.php?option=com_djclassifieds&task=special&cat_id='.$cat_id.'&cid[]='.$l->id.'" class="img_checkt_r" ><img src="'.JURI::base().'/images/tick.png" ></a>';
				} ?>
			</td>
            <td align="center">
                <?php echo $published; ?>
            </td>

        </tr>
        <?php } ?>

    <tfoot>
        <td colspan="9">
            <?php echo $this->pagination->getListFooter(); ?>
        </td>
    </tfoot>
	 </table>
    <input type="hidden" name="option" value="com_djclassifieds" />
	<input type="hidden" name="task" value="items" />
	<input type="hidden" name="t" value="items" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="order" value="<?php echo JRequest::getVar('order') ?>" />
	<input type="hidden" name="ord_t" value="<?php echo JRequest::getVar('ord_t');?> " />
	<?php /*<input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>" /> */ ?>

</form>

<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2010 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(   JText::_( 'AUP_LEVEL-RANK-MEDALS' ), 'addedit' );
JToolBarHelper::custom( 'cpanel', 'default.png', 'default.png', JText::_('AUP_CPANEL'), false );
JToolBarHelper::save( 'savelevelrank' );
JToolBarHelper::cancel( 'cancellevelrank' );
JToolBarHelper::help( 'screen.alphauserpoints', true );

$row = $this->row;
$lists = $this->lists;

JRequest::setVar( 'hidemainmenu', 1 );

$pathimagedefault = JURI::root() ;

$pathicon = JURI::root() . 'components/com_alphauserpoints/assets/images/awards/icons/';
$pathiconbase = 'components'.DS.'com_alphauserpoints'.DS.'assets'.DS.'images'.DS.'awards'.DS.'icons';
$javascript  = 'onchange="changeDisplayIcon();"';

$pathimage = JURI::root() . 'components/com_alphauserpoints/assets/images/awards/large/';
$pathimagebase = 'components'.DS.'com_alphauserpoints'.DS.'assets'.DS.'images'.DS.'awards'.DS.'large';
$javascript2  = 'onchange="changeDisplayImage();"';
?>
<script language="javascript" type="text/javascript">
<!--
function changeDisplayIcon() {
	if (document.adminForm.icon.value !='') {
		document.adminForm.imagelib.src='<?php echo $pathicon; ?>' + document.adminForm.icon.value;
	} else {
		document.adminForm.imagelib.src='<?php echo $pathimagedefault; ?>images/blank.png';
	}
}
function changeDisplayImage() {
	if (document.adminForm.image.value !='') {
		document.adminForm.imagelib2.src='<?php echo $pathimage; ?>' + document.adminForm.image.value;
	} else {
		document.adminForm.imagelib2.src='<?php echo $pathimagedefault; ?>images/blank.png';
	}
}
//-->
</script>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'AUP_DETAILS' ); ?></legend>
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_NAME' ); ?>::<?php echo JText::_('AUP_NAME'); ?>">
					<?php echo JText::_( 'AUP_NAME' ); ?>:
				</span>
			</td>
		  <td>
			<input class="inputbox" type="text" name="rank" id="rank" size="40" maxlength="50" value="<?php echo $row->rank; ?>" />			
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>::<?php echo JText::_('AUP_DESCRIPTION'); ?>">
					<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>:
				</span>
			</td>
			<td>
			<input class="inputbox" type="text" name="description" id="description" size="100" maxlength="255" value="<?php echo $row->description; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_TYPE' ); ?>::<?php echo JText::_('AUP_TYPE_RANK_EXPLAIN'); ?>">
					<?php echo JText::_( 'AUP_TYPE' ); ?>:
				</span>
			</td>
			<td>
			<?php echo $lists['typerank']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_POINTS' ); ?>::<?php echo JText::_('AUP_DESCRIPTION_POINTS_ON_RANK'); ?>">
					<?php echo JText::_( 'AUP_POINTS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="levelpoints" id="levelpoints" size="20" value="<?php echo $row->levelpoints; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_ATTACH_TO_A_RULE' ); ?>::<?php echo JText::_('AUP_ATTACH_TO_A_RULE_DESC'); ?>">
					<?php echo JText::_( 'AUP_ATTACH_TO_A_RULE' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $lists['rules']; ?>			
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_ICON' ); ?>::<?php echo JText::_('AUP_ICON_DESC'); ?>">
					<?php echo JText::_( 'AUP_ICON' ); ?>:
				</span>
			</td>
			<td>
				<?php echo JHTML::_( 'list.images', 'icon', $row->icon , $javascript, $pathiconbase); ?>&nbsp;&nbsp;
				<img src="<?php echo $pathicon; echo $row->icon;?>" name="imagelib" width="16" height="16" border="0" alt=""  style="vertical-align:middle" />
			</td>
		</tr>
		<tr>
          <td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_IMAGE' ); ?>::<?php echo JText::_('AUP_IMAGE_DESC'); ?>"> <?php echo JText::_( 'AUP_IMAGE' ); ?>: </span> </td>
          <td><?php echo JHTML::_( 'list.images', 'image', $row->image , $javascript2, $pathimagebase); ?> </td>
		  </tr>
		<tr>
			<td class="key">&nbsp;
			</td>
			<td><br />				
				<img src="<?php echo $pathimage; echo $row->image;?>" name="imagelib2" border="0" alt="" style="vertical-align:middle" />
			</td>
		</tr>
		</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="redirect" value="levelrank" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
<!-- File Upload Form -->
<form action="<?php echo JURI::base(); ?>index.php?option=com_alphauserpoints&amp;task=upload&amp;tmpl=component&amp;<?php echo JUtility::getToken();?>=1" id="uploadForm" method="post" enctype="multipart/form-data" >
	<fieldset>
		<legend><?php echo JText::_( 'Upload File' ); ?> [ <?php echo JText::_( 'Max' ); ?>&nbsp;<?php echo (10000000 / 1000000); ?>M ]</legend>
		<fieldset class="actions">
			<input type="file" id="file-upload" name="Filedata" />
			<?php echo $this->lists['folder'] . "&nbsp;" ; ?>
			<input type="submit" id="file-upload-submit" value="<?php echo JText::_('Start Upload'); ?>"/>
			<span id="upload-clear"></span>
		</fieldset>
		<ul class="upload-queue" id="upload-queue">
			<li style="display: none" />
		</ul>
	</fieldset>
	<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_alphauserpoints&task=editlevelrank&cid[]='.$row->id); ?>" />
</form>
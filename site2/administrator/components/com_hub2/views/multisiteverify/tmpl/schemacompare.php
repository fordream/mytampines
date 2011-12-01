<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// prepare with indexes as the table name so that we can do proper side by side display
// else hard to match table against same table
$templateTable = $this->template;
$siteTable = $this->site;
?>
<table class="adminlist" style="width:600px">
    <tr>
        <th>Template SQL File</th>
        <th>Site SQL File</th>
    </tr>
    <?php foreach ($templateTable as $tname=>$sql) {?>
    <tr>
        <td><div style="width:300px"><?php echo str_replace("\n","<br />",$sql);?></div></td>
        <td><?php if (array_key_exists($tname,$siteTable)) {?>
                <div style="width:300px"><?php echo str_replace("\n","<br />",$siteTable[$tname]);?></div>
            <?php } else { echo JText::_('TABLE_DOES_NOT_EXIST'); }?>
        </td>
    </tr>
    <?php } ?>
    <?php foreach ($siteTable as $tname=>$sql) {
            if (!array_key_exists($tname,$templateTable)) {
        ?>
    <tr>
        <td><div style="width:300px"><?php echo JText::_('TABLE_DOES_NOT_EXIST');?></div></td>
        <td><div style="width:300px"><?php echo str_replace("\n","<br />",$sql);?></div></td>
    </tr>
    <?php   }
         }?>
</table>
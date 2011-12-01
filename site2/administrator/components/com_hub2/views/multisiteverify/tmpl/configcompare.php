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
foreach ($templateTable as $tname=>$sql) {
    $templateTable[$tname] = $sql;
    //str_replace(') VALUES (','<br />) VALUES (<br />',
    //str_replace('</span>,','</span>,<br />',
    //str_replace("`,","`,<br />",str_replace("',","',<br />",$sql))));
}
foreach ($siteTable as $tname=>$sql) {
    $siteTable[$tname] = $sql;
    //str_replace(') VALUES (','<br />) VALUES (<br />',
    //str_replace('</span>,','</span>,<br />',
    //str_replace("`,","`,<br />",str_replace("',","',<br />",$sql))));
}
?>
<table class="adminlist" style="width:600px">
    <tr>
        <th>Template SQL File</th>
        <th>Site SQL File</th>
    </tr>
    <?php foreach ($templateTable as $tname=>$sql) {?>
    <tr>
        <td style="vertical-align:top"><div style="width:300px;overflow-x:auto;"><?php echo $sql;?></div></td>
        <td style="vertical-align:top"><?php if (array_key_exists($tname,$siteTable)) {?>
                <div style="width:300px;overflow-x:auto"><?php echo $siteTable[$tname];?></div>
            <?php } else { echo JText::_('CONFIG_DOES_NOT_EXIST'); }?>
        </td>
    </tr>
    <?php } ?>
    <?php foreach ($siteTable as $tname=>$sql) {
            if (!array_key_exists($tname,$templateTable)) {
        ?>
    <tr>
        <td  style="vertical-align:top"><div style="width:300px;overflow-x:auto"><?php echo JText::_('CONFIG_DOES_NOT_EXIST');?></div></td>
        <td  style="vertical-align:top"><div style="width:300px;overflow-x:auto"><?php echo $sql;?></div></td>
    </tr>
    <?php   }
         }?>
</table>
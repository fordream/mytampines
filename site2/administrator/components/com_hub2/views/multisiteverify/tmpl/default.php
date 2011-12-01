<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined( '_JEXEC' ) or die();
jimport('joomla.behaviour.modal');

$task = JRequest::getVar('task');
if ($task == 'verifySchemaOnSites') {
    $compareTask = 'compareSchemaFiles';
} else {
    $compareTask = 'compareConfigFiles';
}

function getDifferences($templateChecksum,$localChecksum) {
    $result = array('differ'=>array(),'missing'=>array(),'new'=>array());

    $templateMD5 = array();
    $keys = explode("\n",$templateChecksum);
    foreach ($keys as $key) {
        list($table,$md5) = explode("-",$key);
        $templateMD5[$table] = $md5;
    }
    // print_r($templateMD5);

    $localMD5 = array();
    $keys = explode("\n",$localChecksum);
    foreach ($keys as $key) {
        list($table,$md5) = explode("-",$key);
        $localMD5[$table] = $md5;
    }
    // print_r($localMD5);

    foreach ($templateMD5 as $key=>$val) {
        if (array_key_exists($key,$localMD5)) {
            if ($val !== $localMD5[$key]) {
                $result['differ'][] = $key; // table different on site
            }
        } else {
            $result['missing'][] = $key; // table missing on site
        }
    }
    foreach ($localMD5 as $key=>$val) {
        if (!array_key_exists($key,$templateMD5)) {
            $result['new'][] = $key; // new table on site
        }
    }
    echo '<table class="adminlist"><thead><th>'.JText::_('TABLE_NAME').'</th><th>'.JText::_('STATUS').'</th></thead>';
    foreach ($result as $stat=>$tables) {
        foreach ($tables as $table) {
            echo '<tr><td width="200">'.$table.'</td>';
            if ($stat == 'new') {
                echo '<td width="400">'.JText::_('MISSING_ON_TEMPLATE_PRESENT_ON_SITE').'</td>';
            } else if ($stat == 'missing') {
                echo '<td width="400">'.JText::_('PRESENT_ON_TEMPLATE_MISSING_ON_SITE').'</td>';
            } else {
                //($stat = 'differ')
                echo '<td width="400">'.JText::_('TABLE_IS_DIFFERENT_ON_TEMPLATE_AND_SITE').'</td>';
            }
            echo '</tr>';
        }
    }
    echo '</table>';
}

// print the same sites first
foreach ($this->comparison as $templateSiteName=>$details) {
    $sameSiteDetails = $details['samesites'];
    $diffSiteDetails = $details['diffsites'];
    list($template,$fileMD5,$fileURL) = $details['template'];
    ?>
<h1><?php echo JText::_( 'PROCESSING_ALL_SITES_UNDER_TEMPLATE_SITE' ); ?> (<?php echo $templateSiteName;?>)</h1>
    <?php if (count($sameSiteDetails) > 0) {?>
<h2><?php echo JText::_( 'THE_FOLLOWING_SITES_ARE_THE_SAME_AS_THE_TEMPLATE_SITE' ); ?></h2>
<ul>
<?php foreach ($sameSiteDetails as $siteDetail) {
    list($site,$sfileMD5,$sfileURL) = $siteDetail;
    ?>
  <li>
  <h3><a href="<?php echo $site->url;?>"><?php echo $site->name;?></a></h3>
  </li>
  <?php } ?>
</ul>
  <?php } ?>
  <?php if (count($diffSiteDetails) > 0) {?>
<h2><?php echo JText::_( 'THE_FOLLOWING_SITES_ARE_DIFFERENT_FROM_THE_TEMPLATE_SITE' ); ?></h2>
<ul>
<?php foreach ($diffSiteDetails as $siteDetail) {
    list($site,$sfileMD5,$sfileURL) = $siteDetail;
    ?>
  <li>
  <h3><a href="<?php echo $site->url;?>"><?php echo $site->name;?></a></h3>
  <h4><?php echo JText::_( 'TEMPLATE_SCHEMA_SQL_FILE_IS' ); ?> <a href="<?php echo $fileURL;?>"><?php echo JText::_( 'HERE' ); ?></a>
  <?php echo JText::_( 'AND_SITE_SCHEMA_SQL_FILE_IS' ); ?> <a href="<?php echo $sfileURL;?>"><?php echo JText::_( 'HERE' ); ?></a>.&nbsp;
  <?php
  $link ="index.php?option=com_hub2&task={$compareTask}&tmpl=component";
  $link .= "&templateSiteID=".$template->id;
  $link .= "&siteID=".$site->id;
  ?> <a href="<?php echo $link;?>" class="modal" rel="{handler: 'iframe', size: {x: 680, y: 370}}"><?php echo JText::_('Click here to compare files'); ?></a>
  </h4>
  </li>
  <?php getDifferences($fileMD5,$sfileMD5); ?>
  <?php } ?>
</ul>
  <?php } ?>
  <?php /*
  echo '<h1>Differences between Template ('.$site->name.
  ') and site ('.$subsite->name.')</h1>';
  $this->getDifferences($fileMD5,$sfileMD5);
  echo '<h3>Template config SQL file is <a href="'.
  $downloadFileName.'">here</a>';
  echo ' and Site config SQL file is <a href="'.
  $sdownloadFileName.'">here</a></h3>';

  echo '<h1>Template ('.$site->name.') and site ('.
  $subsite->name.') are same.</h1>';

  if (strcmp($sfileMD5,$fileMD5)!==0) {
  echo '<h1>Differences between Template ('.
  $site->name.') and site ('.$subsite->name.')</h1>';
  } else {
  echo '<h1>Template ('.$site->name.') and site ('.
  $subsite->name.') are same.</h1>';
  }

  */?>
  <?php } ?>

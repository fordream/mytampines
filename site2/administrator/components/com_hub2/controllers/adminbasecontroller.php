<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DS.'components'.DS.
'com_hub2'.DS.'controller.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.
'com_hub2'.DS.'helpers'.DS.'databaseDump.php');

class Hub2AdminBaseController extends Hub2Controller {
    protected function updateScripts(&$db, &$files) {
        $db->setQuery("select filename from #__hub2_sqlchangesexecuted
                    where filename LIKE 'sql/%'");
        $filesExecuted = $db->loadResultArray();
        foreach ($files as $file) {
            // get relative path
            $rfile = str_replace(JPATH_SITE.DS,'',$file);
            $rfile = str_replace(DS,'/',$rfile);
            if (in_array($rfile,$filesExecuted)) {
                echo '<li>File already executed - '.$rfile.'</li>';
            } else {
                echo '<li><b>Executing file - '.$rfile.'</b></li>';
                $errors = array();
                if (!$this->updateDB($db,$file,$errors)) {
                    echo '<h3>ERROR:'.implode('<br />',$errors).'</h3>';
                } else {
                    $db->setQuery('insert into #__hub2_sqlchangesexecuted
                                    (filename,time) VALUES ('.$db->Quote($rfile).',NOW())
                                    ON DUPLICATE KEY update time=NOW()');
                    if (!$db->query()) {
                        echo '<h3>ERROR: could not execute
                                    "insert into #__hub2_sqlchangesexecuted
                                    (filename,time) VALUES ('.$db->Quote($rfile).',NOW())"</h3>';
                    }
                }
            }
        }
    }

    protected function updateDatamodel(&$db,&$files) {
        $db->setQuery("select filename from #__hub2_sqlchangesexecuted where
                    filename LIKE 'dataModel%'");
        $filesExecuted = $db->loadResultArray();
        foreach ($files as $file) {
            // get relative path
            $rfile = str_replace(JPATH_SITE.DS,'',$file);
            $rfile = str_replace(DS,'/',$rfile);
            if (in_array($rfile,$filesExecuted)) {
                echo '<li>File already executed - '.$rfile.'</li>';
            } else {
                echo '<li><b>Executing file - '.$rfile.'</b></li>';
                $errors = array();
                if (!$this->updateDB($db,$file,$errors)) {
                    echo '<h3>ERROR:'.implode('<br />',$errors).'</h3>';
                } else {
                    $db->setQuery('insert into #__hub2_sqlchangesexecuted
                                (filename,time) VALUES ('.$db->Quote($rfile).
                                ',NOW()) ON DUPLICATE KEY update time=NOW()');
                    if (!$db->query()) {
                        echo '<h3>ERROR: could not execute "insert into
                                    #__hub2_sqlchangesexecuted (filename,time) VALUES ('.
                        $db->Quote($rfile).',NOW())"</h3>';
                    }
                }
            }
        }
    }

    public function updateAndSetupDB() {
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.
        'helpers'.DS.'jinstallation.php');
        // get all site details
        $sdb = &JFactory::getDBO();
        $sdb->setQuery('select * from #__hub2_sites');
        $sites = $sdb->loadAssocList();

        // get the datamodel folder and the highest number folder
        $dir = JPATH_SITE.DS.'dataModel';
        $subdirs = array();
        foreach(glob($dir.DS.'*', GLOB_ONLYDIR) as $sub_dir) {
            $subdirs[] = $sub_dir;
        }
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.
            'com_hub2'.DS.'helpers'.DS.'flush.php');
        Hub2FlushHelper::startFlush(false);
        if (!empty($subdirs)) {
            rsort($subdirs);
            $path = $subdirs[0];
            //$path = $dir.DS.$lastDir;
            $files = glob($path.DS.'*.sql');
            // get all dataModels executed for each domain
            echo '<h1>Processing DataModel files</h1>';
            echo '<h2>Processing Hub2</h2><ul>';
            $db = JFactory::getDBO();
            if ($db) {
                $this->updateDatamodel($db,$files);
            } else {
                echo '<h3>ERROR: could not connect to hub</h3>';
            }
            echo '</ul>';
            // flush so we see response instantly
            ob_flush();
            flush();
            foreach ($sites as $site) {
                echo '<h2>Processing Site - '.$site['name'].'</h2><ul>';
                $db = $this->getDatabaseConnection($site);
                if ($db) {
                    $this->updateDatamodel($db,$files);
                } else {
                    echo '<h3>ERROR: could not connect to site</h3>';
                }
                echo '</ul>';
                // flush so we see response instantly
                ob_flush();
                flush();
            }
        }
        // get the scripts folder and the highest number folder
        $dir = JPATH_SITE.DS.'sql'.DS.'com_hub2'.DS.'scripts';
        $subdirs = array();
        foreach(glob($dir.DS.'*', GLOB_ONLYDIR) as $sub_dir) {
            $subdirs[] = $sub_dir;
        }
        if (!empty($subdirs)) {
            rsort($subdirs);
            $path = $subdirs[0];
            //$path = $dir.DS.$lastDir;
            $files = glob($path.DS.'*.sql');
            echo '<h1>Processing Script files</h1>';
            echo '<h2>Processing Hub</h2><ul>';
            $db = JFactory::getDBO();
            if ($db) {
                $this->updateScripts($db,$files);
            } else {
                echo '<h3>ERROR: could not connect to hub</h3>';
            }
            echo '</ul>';
            // flush so we see response instantly
            ob_flush();
            flush();
            foreach ($sites as $site) {
                echo '<h2>Processing Site - '.$site['name'].'</h2><ul>';
                $db = $this->getDatabaseConnection($site);
                if ($db) {
                    $this->updateScripts($db,$files);
                } else {
                    echo '<h3>ERROR: could not connect to site</h3>';
                }
                echo '</ul>';
                // flush so we see response instantly
                ob_flush();
                flush();
            }
        }
        echo '<a href="javascript:history.go(-1);">Go Back</a>';
        Hub2FlushHelper::endFlush(false);
    }

    /**
     * Return a Database connection to the site
     * @param array $site
     * @return Mixed false or JDatabase
     */
    protected function &getDatabaseConnection(&$site) {
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.
        'helpers'.DS.'jinstallation.php');
        $DBtype     = 'mysqli'; //TODO remove this hardcode
        $DBhostname = $site['dbhost'];
        $DBuserName = $site['dbuser'];
        $DBpassword = $site['dbpassword'];
        $DBname     = $site['dbname'];
        $DBPrefix   = $site['dbprefix'];

        $db = & JInstallationHelper::getDBO($DBtype, $DBhostname, $DBuserName,
        $DBpassword, $DBname, $DBPrefix);

        if ( JError::isError($db) ) {
            // connection failed
            $errors[] = JText::sprintf('WARNNOTCONNECTDB', $db->toString());
            return false;
        }

        if ($err = $db->getErrorNum()) {
            // connection failed
            $errors[] = JText::sprintf('WARNNOTCONNECTDB', $db->getErrorNum());
            return false;
        }
        return $db;
    }

    /**
     * Run a given sql file on the given database
     * @param JDatabase $db
     * @param string $dbscheme
     * @param array $errors
     * @return Boolean true on success, error messages are in the errors array
     */
    protected function updateDB(&$db, $dbscheme, &$errors) {

        if (JInstallationHelper::populateDatabase($db, $dbscheme, $errors) > 0) {
            return false;
        }
        return true;
    }

    function dumpDBSchema() {
        // Check for request forgeries
        //JRequest::checkToken() or die( 'Invalid Token' );
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment;filename=hub2dbSchema.sql");
        $db = &JFactory::getDBO();
        $result = Hub2AdminDatabaseDump::dumpSchema();
        echo $result['schema'];
        jexit();
    }


    function dumpConfig() {
        // Check for request forgeries
        //JRequest::checkToken() or die( 'Invalid Token' );
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment;filename=hub2dbConfig.sql");
        $result = Hub2AdminDatabaseDump::dumpConfig(false);
        echo $result['config'];
        jexit();
    }

}
<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.path');
jimport('joomla.filesystem.file');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.
'helpers'.DS.'jinstallation.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.DS.
'services'.DS.'webserviceUtil.php');
require_once('adminbasecontroller.php');

class Hub2ControllerSiteManager extends Hub2AdminBaseController {
    var $_model = null;
    var $_view = null;

    function __construct() {
        parent::__construct();
        $lang = &JFactory::getLanguage();
        $lang->load('com_hub2_admin', JPATH_ADMINISTRATOR);
    }

    function getModelAndView() {
        $viewName = JRequest::getVar('view','dashboard');
        $modelName = JRequest::getVar( 'model',$viewName);
        $this->_model  = $this->getModel( $modelName );
        $this->_model->setState( 'request', JRequest::get( 'post' ) );
        $this->_view = &$this->getView($viewName);
        // Push the model into the view (as default)
        $this->_view->setModel( $this->_model, true);
    }

    /**
     * Display view for the controller according view name from request
     * @return void
     */
    function display() {
        $jApp = &JFactory::getApplication();

        // Set the default view name from the Request
        $viewName = JRequest::getVar('view','dashboard');
        if ($viewName !== 'dashboard') {
            $this->getModelAndView();
        } else {
            $this->_view = &$this->getView($viewName);
        }

        if ($jApp->isAdmin() && ISSITE && IS_MANAGER) {
            $this->addManagerSubMenu($viewName);
        }

        $this->_view->setLayout('default');
        $this->_view->display();
    }

    function addManagerSubMenu($viewName) {
        JSubMenuHelper::addEntry( JText::_('ADMIN_MENU_LABEL_SITEMANAGER'),
                'index.php?option=com_hub2&view=sitemanager',$viewName == 'sitemanager');
    }

    function cancel() {
        // Check for request forgeries
        JRequest::checkToken() or die( 'Invalid Token' );

        if ($this->_view == null) {
            $this->getModelAndView();
        }
        $return = JRequest::getVar( 'return' );
        $cid    = JRequest::getVar( 'cid', array(0), '', 'array' );
        $id     = JRequest::getVar( 'id', $cid[0], '', 'int' );
        $this->_model->setState( 'id', $id );
        $result = $this->_model->checkin();
        $err    = JError::isError( $result ) ? $result->message : JText::_('MSG_EDIT_CANCELLED');

        $this->setRedirect( 'index.php?option=com_hub2&view='.$return, $err );
    }

    /**
     * Edit an existing item.
     *
     * @return void
     * @access public
     */
    function edit() {
        $jApp = &JFactory::getApplication();
        if ($this->_view == null) {
            $this->getModelAndView();
        }

        $cid    = JRequest::getVar( 'cid', array(0), '', 'array' );
        $id     = JRequest::getVar( 'id', $cid[0], '', 'int' );
        JRequest::setVar('task','edit'); // to set for when called by save
        $this->_model->setState( 'id', $id );
        $result = $this->_model->checkout();
        if (!JError::isError($result)) {
            JRequest::setVar('layout','edit');
            $this->_view->setLayout('edit'); // do not set the layout to edit on error
        }
        $this->_view->display();
    }

    /**
     * Save details of an item that is already propagated.
     *
     * @return void
     * @access  public
     */
    function save() {
        // Check for request forgeries
        JRequest::checkToken() or die( 'Invalid Token' );
        jimport( 'joomla.utilities.utility' );

        $request = JRequest::get( 'post' );

        $modelName  = JRequest::getVar( 'model' );
        $view       = JRequest::getVar( 'view' );
        $return     = JRequest::getVar( 'return' );
        $values     = JRequest::getVar( 'jxform', array(), 'post', 'array' );

        $media          = JArrayHelper::getValue( $request, 'media', array(), 'array' );
        $values['media'] = $media;


        if ($this->_view == null) {
            $this->getModelAndView();
        }

        $cid    = JRequest::getVar( 'cid', array(0), '', 'array' );
        $id     = JRequest::getVar( 'id', $cid[0], '', 'int' );
        $this->_model->setState( 'id', $id );

        $errors = array();
        $valid = $this->_model->validateData($values, $errors);
        if (!$valid) {
            JRequest::setVar('errors', $errors);
            $this->_model->setState('values',$values);
            $this->edit();
            return;
        }
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.
            'com_hub2'.DS.'helpers'.DS.'flush.php');
        Hub2FlushHelper::startFlush(false);
        for ($i=0; $i < 100; $i++) {
            Hub2FlushHelper::sendMessage(
            '<div style="display:none">Updating the database ...</div>');
        }
        if (!$id) {
            Hub2FlushHelper::sendMessage('Creating new database');
            $vars  = JArrayHelper::getValue( $request['jxform'], 'newsite', array(), 'array' );
            $values = array_merge($values,$vars);
            $DBtype     = JArrayHelper::getValue($values, 'DBtype', 'mysql');
            $DBhostname = JArrayHelper::getValue($values, 'dbhost', '');
            $DBuserName = JArrayHelper::getValue($values, 'dbuser', '');
            $DBpassword = JArrayHelper::getValue($values, 'dbpassword', '');
            $DBname     = JArrayHelper::getValue($values, 'dbname', '');
            $DBPrefix   = JArrayHelper::getValue($values, 'dbprefix', '');

            $success = false;
            $errors = array();
            if ($this->makeDB($DBtype, $DBhostname, $DBuserName,
            $DBname, $DBpassword, $DBPrefix, $errors)) {
                Hub2FlushHelper::sendMessage('Creating admin user');
                if ($this->createAdminUser($values,$errors)) {
                    $success = true;
                }
            }

            if (!$success) {
                JError::raiseError(500,implode('<br />',$errors));
                return;
            }
        }
        Hub2FlushHelper::sendMessage('Saving data into table');
        // save data into the database
        $result = $this->_model->save( $values );

        $uri = new JURI($values['url']);
        $domain =  preg_replace("/^www\./i",'',$uri->getHost()); // remove beginning www. if any

        Hub2FlushHelper::sendMessage('Creating File system');
        $this->createFileSystemForDomain($domain);

        Hub2FlushHelper::sendMessage('Creating configuration file');
        $this->createConfigurationFile($domain,$values);

        if (JError::isError( $result )) {
            $this->setRedirect( 'index.php?option=com_hub2&view='.$view,
            JText::_($result->message));
            return;
        }
        $this->_model->checkin();
        Hub2FlushHelper::endFlush(false);
        // create new folder if required

        $msg    = JText::_('MSG_ITEM_SAVED');
        $this->setRedirect( 'index.php?option=com_hub2&view='.$return,
        JText::_($msg));
    }

    private function createConfigurationFile($domain,$values) {
        $pname = JPATH_SITE.DS.'multisites'.DS.$domain;
        $fname = $pname.DS.'configuration.php';

        if (file_exists($fname) && !JPath::isOwner($fname)
                && !JPath::setPermissions($fname, '0644')) {
            JError::raiseNotice('SOME_ERROR_CODE',
            'Could not make configuration.php writable in CreateConfig');
        }

        // backup old configuration file if any
        if (file_exists($fname)) {
            if (!copy($fname,$fname.'.bak')) {
                JError::raiseNotice('SOME_ERROR_CODE', 'Could not backup configuration.php');
            }

            if (copy($fname,$fname.'.tmp')) {
                // change the class name from JConfig to SlaveJConfig in the file
                // read the file into a string
                $file_contents = file_get_contents($fname.'.tmp');
                if (!$file_contents) {
                    JError::raiseNotice('SOME_ERROR_CODE',
                    'Could not read temporary configuration.php');
                }
                $newContents = str_replace("JConfig","SlaveJConfig",$file_contents);
                if (!file_put_contents($fname.'.tmp',$newContents)) {
                    JError::raiseNotice('SOME_ERROR_CODE',
                    'Could not write to temporary configuration.php file');
                }
                // get the old config and replace with new values
                require_once($fname.'.tmp');
                $c = new SlaveJConfig();
                $this->updateConfig($domain,$c,$values);
                if (!$this->writeConfig($fname,$c)) {
                    JError::raiseNotice('SOME_ERROR_CODE',
                    'Could not create new configuration.php');
                }
            } else {
                JError::raiseNotice('SOME_ERROR_CODE', 'Could not backup configuration.php');
            }
        } else {
            // get current config object
            $c = new JConfig();
            $this->updateConfig($domain,$c,$values);
            if (!$this->writeConfig($fname,$c)) {
                JError::raiseNotice('SOME_ERROR_CODE', 'Could not create new configuration.php');
            }
        }
    }

    private function createFileSystemForDomain($domain) {
        $pname = JPATH_SITE.DS.'multisites'.DS.$domain;
        if (!file_exists(JPATH_SITE.DS.'multisites')) {
            if (!mkdir(JPATH_SITE.DS.'multisites')) {
                JError::raiseNotice('SOME_ERROR_CODE',
                'Could not create directory - '.JPATH_SITE.DS.'multisites');
            }
        }
        file_put_contents(JPATH_SITE.DS.'multisites'.DS.'index.html','');
        if (!file_exists($pname)) {
            if (!mkdir($pname)) {
                JError::raiseNotice('SOME_ERROR_CODE', 'Could not create directory - '.$pname);
            }
            file_put_contents($pname.DS.'index.html','');
            if (!mkdir($pname.DS.'cache')) {
                JError::raiseNotice('SOME_ERROR_CODE',
                'Could not create directory - '.$pname.DS.'cache');
            }
            file_put_contents($pname.DS.'cache'.DS.'index.html','');

            jimport('joomla.filesystem.folder');
            $config = &JFactory::getConfig();

            $log = $config->getValue('config.log_path');
            $path = $log.DS.'multisites'.DS.$domain.DS.'logs';
            echo "Creating log folder for domain - {$path}<br />";
            if (!JFolder::create($path)) {
                JError::raiseNotice('SOME_ERROR_CODE',
                'Could not create directory - '.$path);
            }
            file_put_contents($path.DS.'index.html','');

            $tmp = $config->getValue('config.tmp_path');
            $path = $tmp.DS.'multisites'.DS.$domain.DS.'tmp';
            echo "Creating tmp folder for domain - {$path}<br />";
            if (!JFolder::create($path)) {
                JError::raiseNotice('SOME_ERROR_CODE',
                'Could not create directory - '.$path);
            }
            file_put_contents($path.DS.'index.html','');
        }
    }

    /**
     * Update configuration using given data
     * @param string $domain
     * @param stdClass $config An Object with configuration data for a site
     * @param array $values
     * @return none
     */
    private function updateConfig($domain,&$config,$values) {
        $config->offline = '0';
        $config->db = $values['dbname'];
        $config->dbprefix = $values['dbprefix'];
        $config->host = $values['dbhost'];
        $config->user = $values['dbuser'];
        $config->password = $values['dbpassword'];
        $config->log_path = $config->log_path.DS.'multisites'.DS.$domain.DS.'logs';
        $config->tmp_path = $config->tmp_path.DS.'multisites'.DS.$domain.DS.'tmp';
        $config->sitename = $values['name'];
        $config->fromname = $values['name'];
        if (!@empty($values['adminemail'])) {
            $config->mailfrom = $values['adminemail'];
        }
    }

    /**
     * Writes configuration to a file from a given object
     * @param string $fname filename
     * @param stdClass $config_value_object object with the configuration details
     * @return true or raises error
     */
    private function writeConfig($fname,$config_value_object) {
        // Try to make configuration.php writeable
        if (file_exists($fname)) {
            if (!JPath::isOwner($fname) && !JPath::setPermissions($fname, '0644')) {
                JError::raiseNotice('SOME_ERROR_CODE',
                'Could not make configuration.php writable in writeConfig');
            }
        }

        $config = new JRegistry('config');
        $config->loadObject($config_value_object);

        // Get the config registry in PHP class format and write it to configuation.php
        if (JFile::write($fname, $config->toString('PHP', 'config', array('class' => 'JConfig')))) {
            return true;
        } else {
            JError::raiseNotice('SOME_ERROR_CODE', 'Could not write to configuration.php file');
            return false;
        }
    }

    /**
     *
     * @return
     */
    private function makeDB($DBtype, $DBhostname, $DBuserName,
    $DBname, $DBpassword, $DBPrefix, &$errors) {

        if (!$DBhostname || !$DBuserName || !$DBname) {
            $this->setError(JText::_('validDBDetails'));
            return false;
        }
        if (!preg_match( '#^[a-zA-Z]+[a-zA-Z0-9_]*$#', $DBPrefix )) {
            $this->setError(JText::_('MYSQLPREFIXINVALIDCHARS'));
            return false;
        }
        if (strlen($DBPrefix) > 15) {
            $this->setError(JText::_('MYSQLPREFIXTOOLONG'));
            return false;
        }
        if (strlen($DBname) > 64) {
            $this->setError(JText::_('MYSQLDBNAMETOOLONG'));
            return false;
        }

        $DBselect   = false;
        $db = & JInstallationHelper::getDBO($DBtype, $DBhostname, $DBuserName,
        $DBpassword, null, $DBPrefix, $DBselect);

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

        //Check utf8 support of database
        $DButfSupport = $db->hasUTF();

        // Try to select the database
        if ( ! $db->select($DBname) ) {
            if (JInstallationHelper::createDatabase($db, $DBname, $DButfSupport)) {
                $db->select($DBname);
            } else {
                $errors[] = JText::sprintf('WARNCREATEDB', $DBname);
                return false;
            }
        } else {

            // pre-existing database - need to set character set to utf8
            // will only affect MySQL 4.1.2 and up
            JInstallationHelper::setDBCharset($db, $DBname);
        }

        $db = & JInstallationHelper::getDBO($DBtype, $DBhostname, $DBuserName,
        $DBpassword, $DBname, $DBPrefix);

        /*
         * We assume since we aren't deleting the database that we need
         * to back it up :)
         */
        if (JInstallationHelper::backupDatabase($db, $DBname, $DBPrefix, $errors)) {
            return false;
        }

        $dbscheme = JPATH_SITE.DS.'database'.DS.'joomla-1.5.20-base+external.sql';

        if (JInstallationHelper::populateDatabase($db, $dbscheme, $errors) > 0) {
            return false;
        }
        $dbscheme = JPATH_SITE.DS.'database'.DS.'hub2-build-db.sql';

        if (JInstallationHelper::populateDatabase($db, $dbscheme, $errors) > 0) {
            return false;
        }
        $dbscheme = JPATH_SITE.DS.'database'.DS.'000_install.sql';

        if (JInstallationHelper::populateDatabase($db, $dbscheme, $errors) > 0) {
            return false;
        }

        /* create stored procs */
        $dbscheme = JPATH_SITE.DS.'database'.DS.'install.sql';
        $content = file_get_contents($dbscheme);
        $content = str_replace('jos_',$DBPrefix,$content);
        $procs = explode('//',$content);
        /* will need mysqli */
        $db2=new mysqli($DBhostname, $DBuserName, $DBpassword, $DBname);
        foreach ($procs as $proc) {
            $db2->multi_query($proc);
        }

        /** update all db scripts */
        // get the datamodel folder and the highest number folder
        $dir = JPATH_SITE.DS.'dataModel';
        $subdirs = array();
        foreach(glob($dir.DS.'*', GLOB_ONLYDIR) as $sub_dir) {
            $subdirs[] = $sub_dir;
        }
        if (!empty($subdirs)) {
            rsort($subdirs);
            $path = $subdirs[0];
            //$path = $dir.DS.$lastDir;
            $files = glob($path.DS.'*.sql');
            // get all dataModels executed for each domain
            echo '<h1>Processing DataModel files</h1>';
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
            $db = JFactory::getDBO();
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

        /*
        // get config from template site and populate
        $util = new Hub2ServiceWebserviceUtil();
        $site = new stdClass;
        $site->id = 1;
        $site->url =  $templateURL ;
        $site->pkey1 = $pkey1;
        $return = $util->sendDataToSite('getConfig',array(),$site,$errors);
        if ($return) {
            $parts = explode('#',$return);
            $fileURL = $parts[0];
            $dbscheme = $site->url.'/'.$fileURL;
            if (JInstallationHelper::populateDatabase($db, $dbscheme, $errors) > 0) {
                return false;
            }
        } else {
            return false;
        }
        */

        return true;
    }

    /**
     * Creates the admin user
     */
    private function createAdminUser(&$vars, &$errors) {
        $DBtype     = JArrayHelper::getValue($vars, 'DBtype', 'mysql');
        $DBhostname = JArrayHelper::getValue($vars, 'dbhost', '');
        $DBuserName = JArrayHelper::getValue($vars, 'dbuser', '');
        $DBpassword = JArrayHelper::getValue($vars, 'dbpassword', '');
        $DBname     = JArrayHelper::getValue($vars, 'dbname', '');
        $DBPrefix   = JArrayHelper::getValue($vars, 'dbprefix', '');

        $adminPassword  = JArrayHelper::getValue($vars, 'adminpassword', '');
        $adminEmail     = JArrayHelper::getValue($vars, 'adminemail', '');

        jimport('joomla.user.helper');

        // Create random salt/password for the admin user
        $salt = JUserHelper::genRandomPassword(32);
        $crypt = JUserHelper::getCryptedPassword($adminPassword, $salt);
        $cryptpass = $crypt.':'.$salt;

        $vars['adminLogin'] = 'admin';

        $db = & JInstallationHelper::getDBO($DBtype, $DBhostname, $DBuserName,
        $DBpassword, $DBname, $DBPrefix);

        // create the admin user
        $installdate    = date('Y-m-d H:i:s');
        $nullDate       = $db->getNullDate();
        $query = "REPLACE INTO #__users VALUES (62, 'Administrator', 'admin', ".
        $db->Quote($adminEmail).", ".$db->Quote($cryptpass).
        ", 'Super Administrator', 0, 1, 25, '$installdate', '$nullDate', '', '')";
        $db->setQuery($query);
        if (!$db->query()) {
            $errors[] = $db->getErrorMsg();
            return false;
        }

        // add the ARO (Access Request Object)
        $query = "REPLACE INTO #__core_acl_aro VALUES (10,'users','62',0,'Administrator',0)";
        $db->setQuery($query);
        if (!$db->query()) {
            $errors[] = $db->getErrorMsg();
            return false;
        }

        // add the map between the ARO and the Group
        $query = "REPLACE INTO #__core_acl_groups_aro_map VALUES (25,'',10)";
        $db->setQuery($query);
        if (!$db->query()) {
            $errors[] = $db->getErrorMsg();
            return false;
        }
        return true;
    }


    public function bulkCreate() {

        $values = array();
        $handle = @fopen(dirname(__FILE__).DS.'..'.DS.'bulkimport'.DS.'bulkimport.csv', "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $parts = explode(',',trim($buffer));
                if (count($parts) == 11) {
                    $values[$parts[0]] = array(
                    'name' => $parts[1],
                    'url' => $parts[2],
                    'host' => $parts[3],
                    'dbname' => $parts[4],
                    'dbuser' => $parts[5],
                    'password' => $parts[6],
                    'dbprefix' => $parts[7],
                    'template_url' => $parts[8],
                    'pkey1' => $parts[9],
                    'adminemail' => $parts[10]
                    );
                } else {
                    echo "Unknown format in line - ".$buffer;
                }
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
        $names = array_keys($values);
        foreach ($names as $domain) {
            echo "Creating filesystem for domain - {$domain}<br />";
            $this->createFileSystemForDomain($domain);

            $s = $this->getSampleConfig();
            $s = str_replace("%domain%",$domain,$s);
            foreach ($values[$domain] as $key=>$value) {
                $s = str_replace("%$key%",$value,$s);
            }
            echo "Creating configuration file for domain - {$domain}<br />";
            $fname = JPATH_SITE.DS.'multisites'.DS.$domain.DS.'configuration.php';
            if (file_exists($fname)) {
                // backup existing file
                copy($fname,$fname.'.bak');
            }
            file_put_contents(JPATH_SITE.DS.'multisites'.DS.$domain.DS.'configuration.php',
            $s);
        }
    }

    private function getSampleConfig() {
        $s = file_get_contents(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.
        DS.'bulkimport'.DS.'gypsampleconfig.txt');
        return $s;
    }
}
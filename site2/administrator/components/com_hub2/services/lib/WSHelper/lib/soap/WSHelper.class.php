<?php
/**
 * Class that generates a WSDL file and creates documentation
 * for your webservices.
 *
 * Patch by Shawn Cook (Shawn@itbytez.com) for the useWSDLCache option
 *
 * @author David Kingma
 * @version 1.5
 */
class WSHelper {
    private $uri;
    private $class = null; //IPReflectionClass object
    private $name; //class name
    private $persistence = SOAP_PERSISTENCE_SESSION;
    private $wsdlfile; //wsdl file name
    private $server; //soap server object

    public $actor;
    public $structureMap = array();
    public $classNameArr = array();
    public $wsdlFolder; //WSDL cache folder
    public $classFolder; // Hyperlocalizer class folder
    public $useWSDLCache = true;

    public $type = SOAP_RPC;
    public $use = SOAP_LITERAL;
    /**
     * Constructor
     * @param string The Uri name
     * @return void
     */
    public function __construct($uri, $class=null) {
        $this->uri = $uri;
        $this->setWSDLCacheFolder($_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF'])."/wsdl/");
        if ($class) {
            $this->setClass($class);
        }
    }

    /**
     * Adds the given class name to the list of classes
     * to be included in the documentation/WSDL/Request handlers
     * @param string
     * @return void
     */
    public function setClass($name) {
        $this->name = $name;
        /** Hyperlocalizer modification */
        $uri = JURI::getInstance();
        $file = $uri->getHost().$uri->getPort().str_replace('/','_',$uri->base(true));
        $this->wsdlfile = $this->wsdlFolder.$file.$this->name.".wsdl";
        /** end Hyperlocalizer modification */
    }

    public function setWSDLCacheFolder($folder) {
        $this->wsdlFolder = $folder;
        //reset wsdlfile
        /** Hyperlocalizer modification */
        $uri = JURI::getInstance();
        $file = $uri->getHost().$uri->getPort().str_replace('/','_',$uri->base(true));
        $this->wsdlfile = $this->wsdlFolder.$file.$this->name.".wsdl";
        /* end Hyperlocalizer modification */
    }
    /**
     * Sets the persistence level for the soap class
     */
    public function setPersistence($persistence) {
        $this->persistence = $persistence;
    }

    /**
     * Handles everything. Makes sure the webservice is handled,
     * documentations is generated, or the wsdl is generated,
     * according to the page request
     * @return void
     */
    public function handle() {
        if(substr($_SERVER['QUERY_STRING'], -4) == 'wsdl') {
            $this->showWSDL();
        } elseif(isset($_REQUEST['HTTP_RAW_POST_DATA']) &&
        strlen($_REQUEST['HTTP_RAW_POST_DATA']) > 0){
            $this->handleRequest();
        } else {
            $this->createDocumentation();
        }
    }
    /** Hyperlocalizer function */
    public function setClassFolder($folder) {
        $this->classFolder = $folder;
    }
    private function structureUpdated() {
        foreach ($this->structureMap as $structure) {
            if (filemtime($this->wsdlfile) < filemtime($this->classFolder.$structure.".php")) {
                return true;
            }
        }
        return false;
    }
    /** end hyperlocalizer function */
    /**
     * Checks if the current WSDL is up-to-date, regenerates if necessary and outputs the WSDL
     * @return void
     */
    public function showWSDL() {
        //check if it's a legal webservice class
        if(!in_array($this->name, $this->classNameArr)) {
            throw new Exception("No valid webservice class.");
        }
        //@TODO: nog een mooie oplossing voor het cachen zoeken
        header("Content-type: text/xml");
        if($this->useWSDLCache && file_exists($this->wsdlfile)) {
            /** Hyperlocalizer using locks to share reading of file */
            $fp = fopen($this->wsdlfile,"r");
            while (flock($fp,LOCK_SH)) {

            }
            readfile($this->wsdlfile);
            flock($fp,LOCK_UN);
            fclose($fp);
            /* end Hyperlocalizer using locks to share reading of file */
        }else{
            //make sure to refresh PHP WSDL cache system
            ini_set("soap.wsdl_cache_enabled",0);
            echo $this->createWSDL();
        }
    }
    /** Hyperlocalizer modification - made this public */
    public function createWSDL() {
        $this->class = new IPReflectionClass($this->name);
        $wsdl = new WSDLStruct($this->uri,
            "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?class=".
        $this->name, $this->type, $this->use);
        $wsdl->setService($this->class);

        try {
            $gendoc = $wsdl->generateDocument();
        } catch (WSDLException $exception) {
            $exception->Display();
            exit();
        }
        /** Hyperlocalizer updated to use locks */
        $fh = fopen($this->wsdlfile, "c+");
        if ($fh) {
            while (!flock($fh, LOCK_EX)) {
                // do an exclusive lock
            }
            ftruncate($fh, 0); // truncate file
            fwrite($fh, $gendoc);
            flock($fh, LOCK_UN); // release the lockflock
            fclose($fh);
        }
        /** end Hyperlocalizer updated to use locks */

        return $gendoc;
    }

    /**
     * Lets the native PHP5 soap implementation handle the request
     * after registrating the class
     * @return void
     */
    private function handleRequest() {
        //check if it's a legal webservice class
        if(!in_array($this->name, $this->classNameArr)) {
            throw new Exception("No valid webservice class.");
        }
        //check cache
        if (!file_exists($this->wsdlfile)) {
            $this->createWSDL();
        }
        $options = Array('actor' => $this->actor, 'classmap' => $this->structureMap);

        header("Content-type: text/xml");
        $this->server = new SoapServer($this->wsdlfile, $options);
        $this->server->setClass($this->name);
        $this->server->setPersistence($this->persistence);

        use_soap_error_handler(true);
        $this->server->handle();
    }

    /**
     * @param string code
     * @param string string
     * @param string actor
     * @param mixed details
     * @param string name
     * @return void
     */
    public function fault($code, $string, $actor, $details, $name='') {
        return $this->server->fault($code, $string, $actor, $details, $name);
    }

    /**
     * Generates the documentations for the webservice usage.
     * @TODO: "int", "boolean", "double", "float", "string", "void"
     * @param string Template filename
     * @return void
     */
    public function createDocumentation($template="templates/docclass.xsl") {
        if(!is_file($template)) {
            throw new WSException("Could not find the template file: '$template'");
        }
        $this->class = new IPReflectionClass($this->name);
        $xtpl = new IPXSLTemplate($template);
        $documentation = Array();
        $documentation['menu'] = Array();
        //loop menu items
        sort($this->classNameArr);//ff sorteren
        foreach($this->classNameArr as $className) {
            $documentation['menu'][] = new IPReflectionClass($className);
        }

        if($this->class){
            $this->class->properties = $this->class->getProperties(false, false);
            $this->class->methods = $this->class->getMethods(false, false);
            foreach((array)$this->class->methods as $method) {
                $method->params = $method->getParameters();
            }

            $documentation['class'] = $this->class;
        }
        echo $xtpl->execute($documentation);
    }
}
?>

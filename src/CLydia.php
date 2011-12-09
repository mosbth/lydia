<?php
/**
 * Main class for Lydia, holds everything.
 *
 * @package LydiaCore
 */
class CLydia implements ISingleton {

	private static $instance = null;

	/**
	 * Constructor
	 */
	protected function __construct() {	
		// set default exception handler
		set_exception_handler(array($this, 'DefaultExceptionHandler'));

		// include the site specific config.php and create a ref to $ly to be used by config.php
		$ly = &$this;
    require(LYDIA_INSTALL_PATH.'/site/config.php');
    
    // create the empty template holder for content to be displayed in templateengine render
    $ly->template = new stdClass();
  }
  
  
	/**
	 * Singleton pattern. Get the instance of the latest created object or create a new one. 
	 * @return CLydia The instance of this class.
	 */
	public static function GetInstance() {
		if(self::$instance == null) {
			self::$instance = new CLydia();
		}
		return self::$instance;
	}


	/**
	 * Create a common exception handler 
	 */
	public static function DefaultExceptionHandler($aException) {
		// CWatchdog to store logs
  	die("<h3>Exceptionhandler</h3><p>File " . $aException->getFile() . " at line" . $aException->getLine() ."<p>Uncaught exception: " . $aException->getMessage() . "<pre>" . print_r($aException->getTrace(), true) . "</pre>");
  }


	/**
	 * Frontcontroller, route to controllers.
	 */
  public function FrontControllerRoute($aController = null, $aAction = null) {
    // Step 1
    // Take current url and divide it in controller, action and parameters
    $query = substr($_SERVER['REQUEST_URI'], strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/')));
    $splits = explode('/', trim($query, '/'));

    // Step 2
    // Set controller, action and parameters
    $controller =  !empty($splits[0]) ? $splits[0] : 'index';
    $action 		=  !empty($splits[1]) ? $splits[1] : 'index';
    $args = $splits;
    unset($args[0]);
    unset($args[1]);
    
    // Step 3
    // Store it
    $this->req	= new stdClass(); // I want to use class instead of array to reference values
    $this->req->query	      = $query;
    $this->req->splits	    = $splits;
    $this->req->controller	= $controller;
    $this->req->action	    = $action;
    $this->req->args	      = $args;
    
    // Step 4
    // Is the module enabled in config.php?
    $moduleExists 	= isset($this->cfg['controllers'][$controller]);
    $moduleEnabled 	= false;
    $class					= false;
    $classExists 		= false;

    if($moduleExists) {
      $moduleEnabled 	= ($this->cfg['controllers'][$controller]['enabled'] == true);
      $class					= $this->cfg['controllers'][$controller]['class'];
      $classExists 		= class_exists($class);
    }
    
    // Step 5
    // Check if controller, action has a callable method in the controller class, if then call it
    if($moduleExists && $moduleEnabled && $classExists) {
      $rc = new ReflectionClass($class);
      if($rc->implementsInterface('IController')) {
        if($rc->hasMethod($action)) {
          $controllerObj = $rc->newInstance();
          $method = $rc->getMethod($action);
          $method->invokeArgs($controllerObj, $this->req->args);
        } else {
          throw new Exception(get_class().' error: Controller does not contain action.');		
        }
      } else {
        throw new Exception(get_class().' error: Controller does not implement interface IController.');
      }
    } 
    // Page not found 404
    else { 
      echo "<p>THIS SHOULD BE A 404 REDIRECT</p>";//$this->FrontControllerRoute('error', 'code404'); // internal redirect
    }
  }   


	/**
	 * Template Engine Render, renders the views using the selected theme.
	 */
  public function TemplateEngineRender() {
    echo $this->template->main;
  }
  
  
};

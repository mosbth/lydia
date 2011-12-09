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
    echo "<hr><h2>Frontcontrollerroute is starting to do its magic.</h2>";
    echo "<p>REQUEST_URI - <code>{$_SERVER['REQUEST_URI']}</code></p>";
    echo "<p>SCRIPT_NAME - <code>{$_SERVER['SCRIPT_NAME']}</code></p>";
    echo "<p>The query containing controller/action/arg1/arg2 is:<br/><code>$query</code></p>";
    echo "<p>This is split into an array:</p><pre>".print_r($splits, true)."</p>";
  	
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
    echo "<p>moduleExists: ".($moduleExists?$moduleExists:'false')."</p>";
    echo "<p>moduleEnabled: ".($moduleEnabled?$moduleEnabled:'false')."</p>";
    echo "<p>class: ".($class?$class:'false')."</p>";
    echo "<p>classExists: ".($classExists?$classExists:'false')."</p>";
    echo "<p>Lets see if the class method is callable.</p>";

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
    echo "<hr><h2>I'm CLydia::TemplateEngineRender, you are most welcome.</h2>";
    echo "<p>The content of \$ly is:</p><pre>".print_r($this, true)."</pre>";
  }
  
  
};

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
	 * Frontcontroller, route to controllers.
	 */
  public function FrontControllerRoute($aController = null, $aAction = null) {
    ;
  }   


	/**
	 * Template Engine Render, renders the views using the selected theme.
	 */
  public function TemplateEngineRender() {
    echo "<p>I'm CLydia::TemplateEngineRender, you are most welcome.</p>";
    echo "<p>REQUEST_URI - {$_SERVER['REQUEST_URI']}</p>";
    echo "<p>SCRIPT_NAME - {$_SERVER['SCRIPT_NAME']}</p>";
    echo "<p>The content of \$ly is:</p><pre>".print_r($this, true)."</pre>";
  }
  
  
};

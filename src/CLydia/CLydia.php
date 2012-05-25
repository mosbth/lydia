<?php
/**
 * Main class for Lydia, holds everything.
 *
 * @package LydiaCore
 */
class CLydia implements ISingleton/*, IModule*/ {

	/**
	 * Members
	 */
	private static $instance = null;
	public $config = array();
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;
	public $user;
	public $timer = array();
	
	
	/**
	 * Constructor
	 */
	protected function __construct() {
		// time page generation
		$this->timer['first'] = microtime(true); 

		// include the site specific config.php and create a ref to $ly to be used by config.php
		$ly = &$this;
    require(LYDIA_SITE_PATH.'/config.php');

		// Start a named session
		session_name($this->config['session_name']);
		session_start();
		$this->session = new CSession($this->config['session_key']);
		$this->session->PopulateFromSession();
		
		// Set default date/time-zone
		date_default_timezone_set('UTC');
		
		// Setup i18n, internationalization and multi-language support
    putenv('LC_ALL='.$this->config['language']);
    setlocale(LC_ALL, $this->config['language']);
    if($this->config['i18n']) {
  		bindtextdomain('lydia', LYDIA_INSTALL_PATH.'/language');
      bind_textdomain_codeset('lydia', 'UTF-8'); 
	  	textdomain('lydia');
	  }
		
		// Create a database object.
		if(isset($this->config['database'][0]['dsn'])) {
  		$this->db = new CDatabase($this->config['database'][0]['dsn']);
  	}
  	
  	// Create a container for all views and theme data
  	$this->views = new CViewContainer();

  	// Create a object for the user
  	$this->user = new CMUser($this);
  }
  
  
  /**
	 * Singleton pattern. Get the instance of the latest created object or create a new one. 
	 * @return CLydia The instance of this class.
	 */
	public static function Instance() {
		return is_null(self::$instance) ? self::$instance = new CLydia() : self::$instance;
	}
	

  /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) {
    switch($action) {
      case 'preinstall':
        // Check gettext
        // Check pdo & sqlite
        // Disable magic quotes
        // check memory limit
        // check writable data-directory
      break;
      
      case 'install':
      break;
      
      default:
        throw new Exception('Unsupported action for this module.');
      break;
    }
  }

 
	/**
	 * Frontcontroller, check url and route to controllers.
	 */
  public function FrontControllerRoute() {
    // Take current url and divide it in controller, method and parameters
    $this->request = new CRequest($this->config['url_type']);
    $this->request->Init($this->config['base_url'], $this->config['routing']);
    $controller = $this->request->controller;
    $method     = $this->request->method;
    $arguments  = $this->request->arguments;
    
    // Is the controller enabled in config.php?
    $controllerExists 	= isset($this->config['controllers'][$controller]);
    $controllerEnabled 	= false;
    $className			    = false;
    $classExists 		    = false;

    if($controllerExists) {
      $controllerEnabled 	= ($this->config['controllers'][$controller]['enabled'] == true);
      $className					= $this->config['controllers'][$controller]['class'];
      $classExists 		    = class_exists($className);
    }
    
    // Check if controller has a callable method in the controller class, if then call it
    if($controllerExists && $controllerEnabled && $classExists) {
      $rc = new ReflectionClass($className);
      if($rc->implementsInterface('IController')) {
         $formattedMethod = str_replace(array('_', '-'), '', $method);
        if($rc->hasMethod($formattedMethod)) {
          $controllerObj = $rc->newInstance();
          $methodObj = $rc->getMethod($formattedMethod);
          if($methodObj->isPublic()) {
            $methodObj->invokeArgs($controllerObj, $arguments);
          } else {
            $this->ShowErrorPage(404, 'Controller method not public.');          
          }
        } else {
          $this->ShowErrorPage(404, 'Controller does not contain method.');
        }
      } else {
        $this->ShowErrorPage(404, 'Controller does not implement interface IController.');
      }
    } 
    else { 
      $this->ShowErrorPage(404, 'Page is not found.');
    }
  }
  
  
  /**
   * ThemeEngineRender, renders the reply of the request to HTML or whatever.
   */
  public function ThemeEngineRender() {
    // Save to session before output anything
    $this->session->StoreInSession();
  
    // Is theme enabled?
    if(!isset($this->config['theme'])) { return; }
    
    // Get the paths and settings for the theme, look in the site dir first
    $themePath 	= LYDIA_INSTALL_PATH . '/' . $this->config['theme']['path'];
    $themeUrl		= $this->request->base_url . $this->config['theme']['path'];

    // Is there a parent theme?
    $parentPath = null;
    $parentUrl = null;
    if(isset($this->config['theme']['parent'])) {
      $parentPath = LYDIA_INSTALL_PATH . '/' . $this->config['theme']['parent'];
      $parentUrl	= $this->request->base_url . $this->config['theme']['parent'];
    }
    
    // Add stylesheet name to the $ly->data array
    $this->data['stylesheet'] = $this->config['theme']['stylesheet'];
    
    // Make the theme urls available as part of $ly
    $this->themeUrl = $themeUrl;
    $this->themeParentUrl = $parentUrl;
    
    // Map menu to region if defined
    if(is_array($this->config['theme']['region_to_menu'])) {
      foreach($this->config['theme']['region_to_menu'] as $key => $val) {
        $this->views->AddString($this->DrawMenu($val), null, $key);
      }
    }

    // Map data to region if defined
    if(is_array($this->config['theme']['view_to_region'])) {
      foreach($this->config['theme']['view_to_region'] as $val) {
        switch($val['type']) {
          case 'string': $this->views->AddStringToRegion($val['region'], $val['content']); break;
          case 'include': $this->views->AddIncludeToRegion($val['region'], $this->LoadView(null, $val['content'])); break;
        }
      }
    }

    // Include the global functions.php and the functions.php that are part of the theme
    $ly = &$this;
    // First the default Lydia themes/functions.php
    include(LYDIA_INSTALL_PATH . '/themes/functions.php');
    // Then the functions.php from the parent theme
    if($parentPath) {
      if(is_file("{$parentPath}/functions.php")) {
        include "{$parentPath}/functions.php";
      }
    }
    // And last the current theme functions.php
    if(is_file("{$themePath}/functions.php")) {
      include "{$themePath}/functions.php";
    }

    // Extract $ly->data to own variables and handover to the template file
    extract($this->data);  // OBSOLETE, use $this->views->GetData() to set variables
    extract($this->views->GetData());
    if(isset($this->config['theme']['data'])) {
      extract($this->config['theme']['data']);
    }

    // Execute the template file
    $templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
    if(is_file("{$themePath}/{$templateFile}")) {
      include("{$themePath}/{$templateFile}");
    } else if(is_file("{$parentPath}/{$templateFile}")) {
      include("{$parentPath}/{$templateFile}");
    } else {
      throw new Exception('No such template file.');
    }
  }


	/**
	 * Display a custom error page.
   *
	 * @param $code integer the code, for example 403 or 404.
	 * @param $message string a message to be displayed on the page.
	 */
	public function ShowErrorPage($code, $message=null) {
	  $errors = array(
	    '403' => array('header' => 'HTTP/1.0 403 Restricted Content', 'title' => t('403, restricted content')),
	    '404' => array('header' => 'HTTP/1.0 404 Not Found', 'title' => t('404, page not found')),
	  );	  
	  if(!array_key_exists($code, $errors)) { throw new Exception(t('Header code is not valid.')); }
    
    $this->views->SetTitle($errors[$code]['title'])
                ->AddIncludeToRegion('primary', $this->LoadView(null, "{$code}.tpl.php"), array('message'=>$message))
                ->AddIncludeToRegion('sidebar', $this->LoadView(null, "{$code}_sidebar.tpl.php"), array('message'=>$message));

    header($errors[$code]['header']);
    $this->ThemeEngineRender();
    exit();
  }


	/**
	 * Redirect to another url and store the session, all redirects should use this method.
   *
	 * @param $url string the relative url or the controller
	 * @param $method string the method to use, $url is then the controller or empty for current controller
	 * @param $arguments string the extra arguments to send to the method
	 */
	public function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
    if(isset($this->config['debug']['db-num-queries']) && $this->config['debug']['db-num-queries'] && isset($this->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }    
    if(isset($this->config['debug']['db-queries']) && $this->config['debug']['db-queries'] && isset($this->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }    
    if(isset($this->config['debug']['memory']) && $this->config['debug']['memory']) {
	    $this->session->SetFlash('memory', memory_get_peak_usage(true));
    }    
    if(isset($this->config['debug']['timer']) && $this->config['debug']['timer']) {
      $this->timer['redirect'] = microtime(true);
	    $this->session->SetFlash('timer', $this->timer);
    }    
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUrl($urlOrController, $method, $arguments));
    exit;
  }


	/**
	 * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
	 *
	 * @param string method name the method, default is index method.
	 * @param $arguments string the extra arguments to send to the method
	 */
	public function RedirectToController($method=null, $arguments=null) {
    $this->RedirectTo($this->request->controller, $method, $arguments);
  }


	/**
	 * Redirect to a controller and method. Uses RedirectTo().
	 *
	 * @param string controller name the controller or null for current controller.
	 * @param string method name the method, default is current method.
	 * @param $arguments string the extra arguments to send to the method
	 */
	public function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
	  $controller = is_null($controller) ? $this->request->controller : null;
	  $method = is_null($method) ? $this->request->method : null;	  
    $this->RedirectTo($this->request->CreateUrl($controller, $method, $arguments));
  }


	/**
	 * Save a message in the session. Uses $this->session->AddMessage()
	 *
   * @param $type string the type of message, for example: notice, info, success, warning, error.
   * @param $message string the message.
   * @param $alternative string the message if the $type is set to false, defaults to null.
   */
  public function AddMessage($type, $message, $alternative=null) {
    if($type === false) {
      $type = 'error';
      $message = $alternative;
    } else if($type === true) {
      $type = 'success';
    }
    $this->session->AddMessage($type, $message);
  }


	/**
	 * Create an url, wrapper and shorter method for $this->request->CreateUrl()
	 *
	 * @param $urlOrController string the relative url or the controller
	 * @param $method string the method to use, $url is then the controller or empty for current
	 * @param $arguments string the extra arguments to send to the method
	 */
	public function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
    return $this->request->CreateUrl($urlOrController, $method, $arguments);
  }


	/**
	 * Create an url to current controller, wrapper for CreateUrl().
	 *
	 * @param $method string the method to use, $url is then the controller or empty for current
	 * @param $arguments string the extra arguments to send to the method
	 */
	public function CreateUrlToController($method=null, $arguments=null) {
    return $this->request->CreateUrl($this->request->controller, $method, $arguments);
  }


  /**
   * Draw HTML for a menu defined in $ly->config['menus'].
   *
   * @param $aMenu string/array either key to the menu in the config-array or array with menu-items.
   * @returns string with the HTML representing the menu.
   */
  public function DrawMenu($aMenu) {
    if(is_array($aMenu)) {
      $menu = $aMenu;
      $class = null;
    } else if(isset($this->config['menus'][$aMenu])) {
      $menu = $this->config['menus'][$aMenu];
      $class = " $aMenu";
    } else {
      throw new Exception('No such menu.');
    }     

    $items = null;
    foreach($menu as $val) {
      if(isset($val['label'])) {
        $selected = null;
        $title = null;
        if(in_array($val['url'], array($this->request->request, $this->request->routed_from)) || substr_compare($val['url'], $this->request->controller, 0) == 0) {
          $selected = " class='selected'";
        }
        if(isset($val['title'])) {
          $title = " title='{$val['title']}'";
        }
        $items .= "<li{$selected}><a{$title} href='" . $this->CreateUrl($val['url']) . "'>{$val['label']}</a></li>\n";
      }
      if(isset($val['items'])) {
        $items .= $this->DrawMenu($val['items']);
      }
    }
    return "<ul class='menu{$class}'>\n{$items}</ul>\n";
  }


  /**
   * Create a breadcrumb from an array.
   *
   * @param array $items to use in breadcrumb.
   * @param string $separator to use as separator.
   * @param array $options to use when creating the breadcrumb.
   * @returns string with the HTML representing the breadcrumb.
   */
  public function CreateBreadcrumb($items=array(), $separator='&raquo;', $options=array()) {
    $default = array(
      'items' => $items,
      'separator' => $separator,
    );
    $options = array_merge($default, $options);
    $crumbs = null;
    foreach($options['items'] as $item) {
      if(isset($item['url'])) {
        $crumbs .= "<li><a href='" . $this->CreateUrl($item['url']) . "'>{$item['label']}</a> {$options['separator']}</li>\n";
      } else {
        $crumbs .= "<li>{$item['label']}</li>\n";
      }
    }
    return "<ul class='breadcrumb'>\n{$crumbs}</ul>\n";
  }


  /**
   * Load a view, looks for the file in LYDIA_SITE_PATH/views/$module and then in 
   * LYDIA_INSTALL_PATH/views/$module.
   *
   * @param string $module name of the module owning the view.
   * @param string $view filename of the view.
   * @returns string with the absolute filename.
   */
  public function LoadView($module, $view) {
    $path1 = LYDIA_SITE_PATH . "/views/$module/$view";
    $path2 = LYDIA_INSTALL_PATH . "/views/$module/$view";
    if(is_file($path1)) {
      return $path1;
    } 
    return $path2;
  }


}
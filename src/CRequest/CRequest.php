<?php
/**
 * Parse the request and identify controller, method and arguments.
 *
 * @package LydiaCore
 */
class CRequest {

	/**
	 * Member variables
	 */
	public $cleanUrl;
  public $querystringUrl;


	/**
	 * Constructor
	 *
	 * Default is to generate url's of type index.php/controller/method/arg1/arg2/arg2
	 *
	 * @param boolean $clean generate clean url's of type /controller/method/arg1/arg2/arg2
	 * @param boolean $querystring generate clean url's of type index.php?q=controller/method/arg1/arg2/arg2
	 */
	public function __construct($urlType=0) {
    $this->cleanUrl       = $urlType= 1 ? true : false;
    $this->querystringUrl = $urlType= 2 ? true : false;
	}


	/**
	 * Create a url in the way it should be created.
	 *
	 * @param $url string the relative url or the controller
	 * @param $method string the method to use, $url is then the controller or empty for current controller.
	 * @param $arguments string the extra arguments to send to the method
	 * @returns string the url
	 */
	public function CreateUrl($url=null, $method=null, $arguments=null) {
    // If fully qualified just leave it.
		if(!empty($url) && (strpos($url, '://') || $url[0] == '/')) {
			return $url;
		}
    
    // Get current controller if empty and method or arguments choosen
    if(empty($url) && (!empty($method) || !empty($arguments))) {
      $url = $this->controller;
    }
    
    // Get current method if empty and arguments choosen
    if(empty($method) && !empty($arguments)) {
      $method = $this->method;
    }
    
    // Create url according to configured style
    $prepend = $this->base_url;
    if($this->cleanUrl) {
      ;
    } elseif ($this->querystringUrl) {
      $prepend .= 'index.php?q=';
    } else {
      $prepend .= 'index.php/';
    }
    $url = trim($url, '/');
    $method = empty($method) ? null : '/' . trim($method, '/');
    $arguments = empty($arguments) ? null : '/' . trim($arguments, '/');    
    return $prepend . rtrim("$url$method$arguments", '/');
  }


  /**
   * Parse the current url request and divide it in controller, method and arguments.
   *
   * Calculates the base_url of the installation. Stores all useful details in $this.
   *
   * @param $baseUrl string use this as a hardcoded baseurl.
   * @param $routing array key/val to use for routing if url matches key.
   */
  public function Init($baseUrl = null, $routing=null) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $scriptName = $_SERVER['SCRIPT_NAME'];    
    
    // Compare REQUEST_URI and SCRIPT_NAME as long they match, leave the rest as current request.
    $i=0;
    $len = min(strlen($requestUri), strlen($scriptName));
    while($i<$len && $requestUri[$i] == $scriptName[$i]) {
      $i++;
    }
    $request = trim(substr($requestUri, $i), '/');
  
    // Remove the ?-part from the query when analysing controller/metod/arg1/arg2
    $queryPos = strpos($request, '?');
    if($queryPos !== false) {
      $request = substr($request, 0, $queryPos);
    }
    
    // Check if request is empty and querystring link is set
    if(empty($request) && isset($_GET['q'])) {
      $request = trim($_GET['q']);
    }
    
    // Check if url matches an entry in routing table
    $routed_from = null;
    if(is_array($routing) && isset($routing[$request]) && $routing[$request]['enabled']) {
      $routed_from = $request;
      $request = $routing[$request]['url'];
    }
    
    // Split the request into its parts
    $splits = explode('/', $request);
    
    // Set controller, method and arguments
    $controller =  !empty($splits[0]) ? $splits[0] : 'index';
    $method 		=  !empty($splits[1]) ? $splits[1] : 'index';
    $arguments = $splits;
    unset($arguments[0], $arguments[1]); // remove controller & method part from argument list
    
    // Prepare to create current_url and base_url
    $currentUrl = $this->GetCurrentUrl();
    $parts 	    = parse_url($currentUrl);
    $baseUrl 		= !empty($baseUrl) ? $baseUrl : "{$parts['scheme']}://{$parts['host']}" . (isset($parts['port']) ? ":{$parts['port']}" : '') . rtrim(dirname($scriptName), '/');
    
    // Store it
    $this->base_url 	  = rtrim($baseUrl, '/') . '/';
    $this->current_url  = $currentUrl;
    $this->request_uri  = $requestUri;
    $this->script_name  = $scriptName;
    $this->routed_from  = $routed_from;
    $this->request      = $request;
    $this->splits	      = $splits;
    $this->controller	  = $controller;
    $this->method	      = $method;
    $this->arguments    = $arguments;
  }


	/**
	 * Get the url to the current page. 
	 */
	public function GetCurrentUrl() {
    $url = "http";
    $url .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
    $url .= "://";
    $serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
    (($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' : ":{$_SERVER['SERVER_PORT']}");
    $url .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars($_SERVER["REQUEST_URI"]);
		return $url;
	}


}
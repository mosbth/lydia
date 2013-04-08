<?php
/**
 * Holding a instance of CLydia to enable use of $this in subclasses and provide some helpers.
 *
 * @package LydiaCore
 */
class CObject {

	/**
	 * Members
	 */
	protected $ly;
	protected $config;
	protected $request;
	protected $db;
	protected $views;
	protected $session;
	protected $user;


	/**
	 * Constructor, can be instantiated by sending in the $ly reference.
	 */
	protected function __construct($ly=null) {
	  if(!$ly) {
	    $ly = CLydia::Instance();
	  }
	  $this->ly       = &$ly;
    $this->config   = &$ly->config;
    $this->request  = &$ly->request;
    $this->db       = &$ly->db;
    $this->views    = &$ly->views;
    $this->session  = &$ly->session;
    $this->user     = &$ly->user;
	}


	/**
	 * Wrapper for same method in CLydia. See there for documentation.
	 */
	protected function ShowErrorPage($code, $message=null) {
    $this->ly->ShowErrorPage($code, $message);
  }


	/**
	 * Wrapper for same method in CLydia. See there for documentation.
	 */
	protected function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
    $this->ly->RedirectTo($urlOrController, $method, $arguments);
  }


  /**
   * Wrapper for same method in CLydia. See there for documentation.
   *
   */
  protected function RedirectToCurrent() {
    $this->ly->RedirectToCurrent();
  }


	/**
	 * Wrapper for same method in CLydia. See there for documentation.
	 */
	protected function RedirectToController($method=null, $arguments=null) {
    $this->ly->RedirectToController($method, $arguments);
  }


	/**
	 * Wrapper for same method in CLydia. See there for documentation.
	 */
	protected function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
    $this->ly->RedirectToControllerMethod($controller, $method, $arguments);
  }


  /**
   * Wrapper for same method in CLydia. See there for documentation.
   */
  protected function RedirectToCurrentControllerMethod($arguments=null) {
    $this->ly->RedirectToCurrentControllerMethod($arguments);
  }


	/**
	 * Wrapper for same method in CLydia. See there for documentation.
	 */
  protected function AddMessage($type, $message, $alternative=null) {
    return $this->ly->AddMessage($type, $message, $alternative);
  }


  /**
   * Wrapper for same method in CLydia. See there for documentation.
   */
  protected function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
    return $this->ly->CreateUrl($urlOrController, $method, $arguments);
  }


  /**
   * Wrapper for same method in CLydia. See there for documentation.
   */
  protected function CreateCleanUrl($urlOrController=null, $method=null, $arguments=null) {
    return $this->ly->CreateCleanUrl($urlOrController, $method, $arguments);
  }


  /**
   * Wrapper for same method in CLydia. See there for documentation.
   */
  protected function CreateUrlToController($method=null, $arguments=null) {
    return $this->ly->CreateUrlToController($method, $arguments);
  }


  /**
   * Wrapper for same method in CLydia. See there for documentation.
   */
  protected function CreateUrlToControllerMethod($arguments=null) {
    return $this->ly->CreateUrlToControllerMethod($arguments);
  }



  /**
   * Wrapper for same method in CLydia. See there for documentation.
   */
  protected function CreateUrlToControllerMethodArguments() {
    return $this->ly->CreateUrlToControllerMethodArguments();
  }



  /**
   * Wrapper for same method in CLydia. See there for documentation.
   */
  protected function CreateMenu($options) {
    return $this->ly->CreateMenu($options);
  }


  /**
   * Wrapper for same method in CLydia. See there for documentation.
   */
  protected function CreateBreadcrumb($options) {
    return $this->ly->CreateBreadcrumb($options);
  }


  /**
	 * Wrapper for same method in CLydia. See there for documentation. Trys to find view 
	 * related to class, if it fails it tries to find view related to parent class.
   */
  protected function LoadView($view) {
    $file = $this->ly->LoadView(get_class($this), $view);
    if(!$file) {
      $file = $this->ly->LoadView(get_parent_class($this), $view);
    }
    if(!$file) {
      throw new Exception(t('No such view @viewname.', array('@viewname' => $view)));
    }
    return $file;
  }


}
  
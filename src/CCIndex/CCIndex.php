<?php
/**
 * Standard controller layout.
 * 
 * @package LydiaCore
 */
class CCIndex extends CObject implements IController {

  /**
   * Constructor
   */
  public function __construct() { parent::__construct(); }
  

  /**
   * Implementing interface IController. All controllers must have an index action.
   */
  public function Index() {     
    $this->views->SetTitle(t('Home'))
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'))
                ->AddIncludeToRegion('sidebar', $this->LoadView('sidebar.tpl.php')
    );
  }


  /**
   * Implementing a default method for this controller. This is used for all actions that does not have a 
   * corresponding method in the controller, its a catch-all method.
   */
  public function CatchAll() {
    // Check if this is the last step of the installation process or just some random call
    if($this->request->method == 'step7') {
      $this->views->SetTitle(t('Home'))->AddIncludeToRegion('primary', $this->LoadView('installed.tpl.php'));
    } else {
      $this->ShowErrorPage(404, t('Page is not found.'));
    }
  }


} 
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
    $modules = new CMModules();
    $controllers = $modules->AvailableControllers();
    $this->views->SetTitle(t('Home'))
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'))
                ->AddIncludeToRegion('sidebar', $this->LoadView('sidebar.tpl.php'), array('controllers'=>$controllers));
  }


} 
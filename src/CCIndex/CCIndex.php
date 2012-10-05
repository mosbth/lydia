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
   * Installation phase.
   */
  public function Install($step=null) {
    global $ly;
    include(__DIR__.'/config_install.php');
    
    $data = array(
      'dsn' => isset($this->config['database'][0]['dsn']) ? $this->config['database'][0]['dsn'] : t('Setting for default database is missing in config.php.'),
    );
    
    $this->views->SetTitle(t('Install Lydia'));
    
    if(!$step || $step == 'start') {
      $this->views->AddIncludeToRegion('primary', $this->LoadView('install_0.tpl.php'));
    } else if($step == 'step1') {
      $this->views->AddIncludeToRegion('primary', $this->LoadView('install_1.tpl.php'));
    } else if($step == 'step2') {
      $this->views->AddIncludeToRegion('primary', $this->LoadView('install_2.tpl.php'));
    } else if($step == 'step3') {
      $this->views->AddIncludeToRegion('primary', $this->LoadView('install_3.tpl.php'), $data);
    } else if($step == 'step4') {
      $this->views->AddIncludeToRegion('primary', $this->LoadView('install_4.tpl.php'));
    } else if($step == 'step5') {
      $this->views->AddIncludeToRegion('primary', $this->LoadView('install_5.tpl.php'));
    } else if($step == 'step6') {
      $this->views->AddIncludeToRegion('primary', $this->LoadView('install_6.tpl.php'));
    } else {
      throw new Exception(t('No such step during installation phase.'));
    }
  }


} 
<?php
/**
 * To manage and analyse all modules of Lydia.
 * 
 * @package LydiaCore
 */
class CCModules extends CObject implements IController {

  /**
   * Properties
   */
  public $breadcrumb;
  

  /**
   * Constructor
   */
  public function __construct() { 
    parent::__construct(); 
    $this->breadcrumb = array(
        array('label' => t('Module Manager'), 'url' => $this->CreateUrlToController()),
    );
  }


  /**
   * Show a index-page and display what can be done through this controller.
   */
  public function Index() {
    $modules = new CMModules();
    $controllers = $modules->AvailableControllers();
    $allModules = $modules->ReadAndAnalyse();
    $supportedActions = $modules->GetSupportedActions();
    $this->views->SetTitle(t('Manage Modules'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'), array('controllers'=>$controllers, 'actions'=>$supportedActions))
                ->AddIncludeToRegion('sidebar', $this->LoadView('sidebar.tpl.php'), array('modules'=>$allModules));
  }


  /**
   * Show a index-page and display what can be done through this controller, replaced by Action('install').
   *
   * @deprecated v0.3.01
   */
  public function Install() {
    $this->Action('install');
  }


  /**
   * Perform an action to all managable modules supporting the specific action.
   *
   * @param string $action the action.
   * @param string $secret pass this to enable management without being logged in.
   */
  public function Action($action=null, $secret=null) {
    if($secret && $action != 'crontab') {
      $this->ShowErrorPage(403, t('You need admin-privileges to access this page.'));
    }
    
    $modules = new CMModules($secret);
    $results = $modules->InvokeActionToManage($action);
    $allModules = $modules->ReadAndAnalyse();
    $this->breadcrumb[] = array('label' => t('Action: !action', array('!action'=>$action)), 'url' => $this->CreateUrlToController('action', $action));

    $this->views->SetTitle(t('!action - Results from action', array('!action'=>$action)))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddIncludeToRegion('primary', $this->LoadView('action.tpl.php'), array('modules'=>$results, 'action'=>$action))
                ->AddIncludeToRegion('sidebar', $this->LoadView('sidebar.tpl.php'), array('modules'=>$allModules));
  }


  /**
   * Execute a series of SQL commands to load the database.
   */
  public function ExecuteSQL() {
    $modules = new CMModules();
    $form = new CFormModules();
    $form->CreateExecuteSQL($modules);
    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', t('Some fields did not validate and the form could not be processed.'));
      $this->RedirectToControllerMethod();
    } else if($status == true) {
      $this->AddMessage('success', t('SQL was executed.'));
      $this->session->SetFlash('results', $status);
      $this->RedirectToControllerMethod();
    }
    
    //$results = $modules->InvokeActionToManage($action);
    $results = $this->session->GetFlash('results');
    $allModules = $modules->ReadAndAnalyse();
    $this->breadcrumb[] = array('label' => t('Execute SQL'), 'url' => $this->CreateUrlToController('execute-sql'));

    $this->views->SetTitle(t('Execute SQL'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddIncludeToRegion('primary', $this->LoadView('execute_sql.tpl.php'), array('form'=>$form, 'results'=>$results))
                ->AddIncludeToRegion('sidebar', $this->LoadView('sidebar.tpl.php'), array('modules'=>$allModules));
  }


  /**
   * Show a module and its parts.
   *
   * @param string $module modulename.
   */
  public function View($module=null) {
    if(!preg_match('/^C[a-zA-Z]+$/', $module)) {throw new Exception(t('Invalid characters in module name.'));}
    // Check that module name exists or redirect to 404.
    $modules = new CMModules();
    $controllers = $modules->AvailableControllers();
    $allModules = $modules->ReadAndAnalyse();
    $aModule = $modules->ReadAndAnalyseModule($module);
    $this->breadcrumb[] = array('label' => t('Module: @module', array('@module'=>$module)), 'url' => $this->CreateUrlToController('view', $module));

    $this->views->SetTitle(t('Manage Modules'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddIncludeToRegion('primary', $this->LoadView('view.tpl.php'), array('module'=>$aModule))
                ->AddIncludeToRegion('sidebar', $this->LoadView('sidebar.tpl.php'), array('modules'=>$allModules));
  }


}
<?php
/**
 * For fresh installing Lydia.
 * 
 * @package LydiaCore
 */
class CCInstall extends CObject implements IController {


  /**
   * Constructor
   */
  public function __construct() { 
    parent::__construct(); 
    global $ly;
    include(__DIR__.'/config_install.php');
  }
  

  /**
   * Implementing interface IController. All controllers must have an index action.
   */
  public function Index() {     
    $this->views->SetTitle(t('Install Lydia'));
    $this->views->AddIncludeToRegion('primary', $this->LoadView('install_0.tpl.php'));
  }


  /**
   * Step 1 of the installation phase.
   */
  public function Step1() {     
    $this->views->SetTitle(t('Verify the server environment'));
    $this->views->AddIncludeToRegion('primary', $this->LoadView('install_1.tpl.php'));
  }


  /**
   * Step 2 of the installation phase.
   */
  public function Step2() {     
    $this->views->SetTitle(t('Verify writable site-directory'));
    $this->views->AddIncludeToRegion('primary', $this->LoadView('install_2.tpl.php'));
  }


  /**
   * Step 3 of the installation phase.
   */
  public function Step3() {
    $data =  array(
      'dsn' => isset($this->config['database'][0]['dsn']) ? $this->config['database'][0]['dsn'] : t('Setting for default database is missing in site/config.php.')
    );

    $this->views->SetTitle(t('Verify default database is enabled'));
    $this->views->AddIncludeToRegion('primary', $this->LoadView('install_3.tpl.php'), $data);
  }


  /**
   * Step 4 of the installation phase.
   */
  public function Step4() {
    $elements = array(
      'email' => array(
        'type' => 'text',
        'label' => t('Email:'),
        'required' => true,
        'autofocus' => true,
        'validation' => array('not_empty', 'email_adress'),
      ),
      'username' => array(
        'type' => 'text',
        'label' => t('Username:'),
        'value' => 'root',
        'required' => true,
        'autofocus' => true,
        'validation' => array('not_empty'),
      ),
      'password1' => array(
        'type' => 'password',
        'label' => t('Password:'),
        'required' => true,
        'validation' => array('not_empty'),
      ),
      'password2' => array(
        'type' => 'password',
        'label' => t('Password again:'),
        'required' => true,
        'validation' => array('not_empty', 'match' => 'password1'),
      ),
      'doCreate' => array(
        'type' => 'submit',
        'value' => t('Create'),
        'callback' => function($form) {
          $cmuser = new CMUser();
          list($status, $message) = $cmuser->Manage('install-root', array('rootEmail' => $form['email']['value'], 'rootUserName' => $form['username']['value'], 'rootPassword' => $form['password1']['value']));
          CLydia::Instance()->AddMessage($status, $message);
          return $status === 'success' ? true : false;
        }
      ),
    );
    
    // Check if there is a root user
    $rootUser = $this->user->GetUserById(1);

    // Manage the form which creates the root user
    $form = new CForm(array(), $elements);

    $status = null;
    if(!$rootUser) {
      $status = $form->Check();
      if($status === true) {
        $this->RedirectToCurrent();
      } else if($status === false) {
        $this->RedirectToCurrent();
      }
    }

    $this->views->SetTitle(t('Create the root user'));
    $this->views->AddIncludeToRegion('primary', $this->LoadView('install_4.tpl.php'), array('form' => $form, 'rootUser' => $rootUser));
  } 


  /**
   * Step 5 of the installation phase.
   */
  public function Step5() {
    $elements = array(
      'username' => array(
        'type' => 'text',
        'label' => t('Username or email:'),
        'value' => 'root',
        'required' => true,
        'autofocus' => true,
        //'validation' => array('not_empty'),
      ),
      'password' => array(
        'type' => 'password',
        'label' => t('Password:'),
        'required' => true,
        //'validation' => array('not_empty'),
      ),
      'doLogin' => array(
        'type' => 'submit',
        'value' => t('Login'),
        'callback' => function($form) {
          $ly = CLydia::Instance();
          $res = $ly->user->Login($form['username']['value'], $form['password']['value']);
          $isAdmin = $ly->user->IsAdmin();
          if($res && $isAdmin) {
            $ly->AddMessage('success', t('You have successfully logged in as the root user.'));
          } else {
            $ly->user->Logout();
            $ly->AddMessage('error', t('The username or password did not match or you are not the root user.'));
          }
          return $res && $isAdmin;
        }
      ),
      'doLogout' => array(
        'type' => 'submit',
        'value' => t('Logout'),
        'callback' => function($form) {
          CLydia::Instance()->AddMessage('success', t('You have logged out.'));
          return CLydia::Instance()->user->Logout();
        }
      ),
    );

    // Manage the for which creates the root user
    $form = new CForm(array(), $elements);

    $status = $form->Check();
    if($status === true) {
      $this->RedirectToCurrent();
    } else if($status === false) {
      $this->RedirectToCurrent();
    }

    $isAdmin = $this->user->IsAdmin();
    if($isAdmin) {
      $form->RemoveElement('username')->RemoveElement('password')->RemoveElement('doLogin');
    } else {
      $form->RemoveElement('doLogout');
    }

    $this->views->SetTitle(t('Login as the root user'));
    $this->views->AddIncludeToRegion('primary', $this->LoadView('install_5.tpl.php'), array('form' => $form, 'isAdmin' => $isAdmin));
  }


  /**
   * Step 6 of the installation phase.
   */
  public function Step6() {
    $modules = new CMModules();
    $data['modules'] = $modules->InvokeActionToManage('install');
    $this->views->SetTitle(t('Install all modules'));
    $this->views->AddIncludeToRegion('primary', $this->LoadView('install_6.tpl.php'), $data);
  }


  /**
   * Step 7 of the installation phase.
   */
  public function Step7() {
    $installController = 'install';
    $status = ($this->config['controllers'][$installController] && $this->config['controllers'][$installController]['enabled']) ? 'enabled' : 'disabled';
    $this->views->SetTitle(t('Done - disable installation'));
    $this->views->AddIncludeToRegion('primary', $this->LoadView('install_7.tpl.php'), array('status' => $status));
  }



  /**
   * Simluate a login
   */
  public function Login() {
    return $this->Step5();
  }



} 
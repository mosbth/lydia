<?php
/**
 * A user controller to manage login and view edit the user profile.
 * 
 * @package LydiaCore
 */
class CCUser extends CObject implements IController {

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
        array('label' => t('User Control Panel'), 'url' => $this->CreateUrlToController()),
    );
  }


  /**
   * Show profile information of the user if logged in, or redirect to login.
   */
  public function Index() {
    $if = new CInterceptionFilter();
    $if->IsRegularUserOrForbidden();
    
    $if = new CInterceptionFilter();
    $if->AuthenticatedOrLogin();
    $this->views->SetTitle(t('User Control Panel'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddIncludeToRegion('primary', $this->loadView('index.tpl.php'), array('user'=>$this->user));
  }
  

  /**
   * Authenticate and login a user.
   */
  public function Login() {
    $form = new CFormUser();
    $form->CreateLogin($this->user);
    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', t('Failed to login, user or password does not match.'));
      $this->RedirectToControllerMethod();
    } else if($status === true) {
      $whereTo = $this->session->GetFlash('redirect_on_success');
      if($whereTo) {
        $this->RedirectTo($whereTo);
      } else {
        $this->RedirectToController('index');
      }
    }

    // remember where we going on success
    $whereTo = $this->session->GetFlash('redirect_on_success');
    if($whereTo) {
      $this->session->SetFlash('redirect_on_success', $whereTo);    
    }

    $this->views->SetTitle(t('Login'))
                ->AddIncludeToRegion('primary', $this->loadView('login.tpl.php'), array(
                  'login_form' => $form,
                  'allow_create_user' => CLydia::Instance()->config['create_new_users'],
                  'create_user_url' => $this->CreateUrlToController('create'),
                ));
  }
  

  /**
   * View and edit user profile.
   */
  public function Profile() {    
    $if = new CInterceptionFilter();
    $if->IsRegularUserOrForbidden();
    
    $form = new CFormUser();
    $form->CreateProfile($this->user);
    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', t('Some fields did not validate and the form could not be processed.'));
      $this->RedirectToControllerMethod();
    } else if($status === true) {
      $this->AddMessage('success', t('Updated profile.'));
      $this->RedirectToControllerMethod();
    }
    
    $this->breadcrumb[] = array('label' => t('Profile'), 'url' => $this->CreateUrlToController('profile'));

    $this->views->SetTitle(t('User profile'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddIncludeToRegion('primary', $this->LoadView('profile.tpl.php'), array('form'=>$form->GetHTML()));
  }
  

  /**
   * Change password.
   */
  public function ChangePassword() {    
    $if = new CInterceptionFilter();
    $if->IsRegularUserOrForbidden();
    
    $form = new CFormUser();
    $form->CreateChangePassword($this->user);
    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', t('The password could not be changed, ensure that all fields match.'));
      $this->RedirectToControllerMethod();
    } else if($status === true) {
      $this->AddMessage('success', t('Saved new password.'));
      $this->RedirectToControllerMethod();
    }
    
    $this->breadcrumb[] = array('label' => t('Change password'), 'url' => $this->CreateUrlToController('change-password'));

    $this->views->SetTitle(t('User change password'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddIncludeToRegion('primary', $this->LoadView('change_password.tpl.php'), array('form'=>$form->GetHTML()));
  }
  

  /**
   * Logout a user.
   */
  public function Logout() {
    $this->user->Logout();
    $this->RedirectToController('login');
  }
  

  /**
   * Create a new user.
   */
  public function Create() {
    $if = new CInterceptionFilter();
    $if->CreateNewUserOrForbidden();
    
    $form = new CFormUser();
    $form->CreateUserCreate($this->user);
    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', t('The account could not be created.'));
      $this->RedirectToControllerMethod();
    } else if($status === true) {
      $this->AddMessage('success', t('Your have successfully created a new account.'));
      $this->RedirectToController('index');
    }
    
    $this->views->SetTitle('Create user')
                ->AddIncludeToRegion('primary', $this->LoadView('create.tpl.php'), array('form' => $form->GetHTML()));     
  }
  

} 
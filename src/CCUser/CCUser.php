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
    $this->Overview();
  }
  

  /**
   * Show profile information of the user if logged in, or redirect to login.
   */
  public function Overview() {
    $if = new CInterceptionFilter();
    $if->IsRegularUserOrForbidden()->AuthenticatedOrLogin();

    $data = array(
      'header'  => $this->LoadView('header.tpl.php'),
      'user'    => $this->user,
      'navbar'  => $this->CreateMenu('navbar-ucp'),
    );

    $this->breadcrumb[] = array('label' => t('Overview'), 'url' => $this->CreateUrlToControllerMethod());

    $this->views->SetTitle(t('User Control Panel'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddClassToRegion('custom', 'ucp')
                ->AddIncludeToRegion('custom', $this->loadView('index.tpl.php'), $data);
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
        $this->RedirectToController();
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
  

  /**
   * View and edit user profile.
   */
  public function Profile() {    
    $if = new CInterceptionFilter();
    $if->IsRegularUserOrForbidden();

    $form = new CForm(array(), array(
        'acronym' => array(
          'type'  => 'hidden',
          'value' => $this->user['acronym'],
        ),
        'acronym' => array(
          'type'        => 'text',
          'label'       => t('Acronym:'),
          'value'       => $this->user['acronym'],
          'readonly'    => true,
          'required'    => true,
          'validation'  => array('not_empty'),
        ),        
        'name' => array(
          'type'        => 'text',
          'label'       => t('Name:'),
          'value'       => $this->user['name'],
          'required'    => true,
          'autofocus'   => true,
          'validation'  => array('not_empty'),
        ),        
        'doSave' => array(
          'type'      => 'submit',
          'value'     => t('Save'),
          'callback'  => function($f) {
            return Clydia::Instance()->user->ChangeOwnProfile($f->Value('acronym'), $f->Value('akronym'), $f->Value('name'));
          }
        ),
      )
    );

    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', t('The profile could not be saved.'));
      $this->RedirectToControllerMethod();
    }
    else if ($status === true) {
      $this->AddMessage('success', t('Saved profile.'));
      $this->RedirectToControllerMethod();
    }

    $data = array(
      'header'   => $this->LoadView('header.tpl.php'),
      'user'     => $this->user,
      'navbar'   => $this->CreateMenu('navbar-ucp'),
      'form'     => $form->GetHTML(),
    );

    $this->breadcrumb[] = array('label' => t('Profile'), 'url' => $this->CreateUrlToControllerMethod());

    $this->views->SetTitle(t('Edit profile'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddClassToRegion('custom', 'ucp')
                ->AddIncludeToRegion('custom', $this->loadView('profile.tpl.php'), $data);

/*    $if = new CInterceptionFilter();
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
*/  }
  

  /**
   * Give a form to the user so the user can change the password.
   *
   */
  public function ChangePassword() {    
    $if = new CInterceptionFilter();
    $if->IsRegularUserOrForbidden();

    $form = new CForm(array(), array(
        'acronym' => array(
          'type'  => 'hidden',
          'value' => $this->user['acronym'],
        ),
        'password1' => array(
          'type'        => 'password',
          'label'       => t('Current password:'),
          'required'    => true,
          'autofocus'   => true,
          'validation'  => array('not_empty'),
        ),        
        'password2' => array(
          'type'        => 'password',
          'label'       => t('New password:'),
          'required'    => true,
          'validation'  => array('not_empty'),
        ),        
        'password3' => array(
          'type'        => 'password',
          'label'       => t('New password again:'),
          'required'    => true,
          'validation'  => array('not_empty', 'match'=>'password2'),
        ),        
        'doChange' => array(
          'type'      => 'submit',
          'value'     => t('Change password'),
          'callback'  => function($f) {
            return Clydia::Instance()->user->ChangeOwnPasswordVerify($f->Value('acronym'), $f->Value('password1'), $f->Value('password2'), $f->Value('password3'));
          }
        ),
      )
    );

    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', t('The password could not be changed, ensure that all fields match and the current password is correct.'));
      $this->RedirectToControllerMethod();
    }
    else if ($status === true) {
      $this->AddMessage('success', t('Saved new password.'));
      $this->RedirectToControllerMethod();
    }

    $data = array(
      'header'   => $this->LoadView('header.tpl.php'),
      'user'     => $this->user,
      'navbar'   => $this->CreateMenu('navbar-ucp'),
      'form'     => $form->GetHTML(),
    );

    $this->breadcrumb[] = array('label' => t('Change password'), 'url' => $this->CreateUrlToControllerMethod());

    $this->views->SetTitle(t('Change password'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddClassToRegion('custom', 'ucp')
                ->AddIncludeToRegion('custom', $this->loadView('change_password.tpl.php'), $data);
  }
  

  /**
   * Give a form to the user so the user modify their emailadress.
   *
   */
  public function Email() {    
    $if = new CInterceptionFilter();
    $if->IsRegularUserOrForbidden();

    $form = new CForm(array(), array(
        'acronym' => array(
          'type'  => 'hidden',
          'value' => $this->user['acronym'],
        ),
        'mail' => array(
          'type'        => 'text',
          'label'       => t('Current email adress:'),
          'value'       => $this->user['email'],
          'required'    => true,
          'autofocus'   => true,
          'validation'  => array('not_empty', 'email_adress'),
        ),        
        'doSave' => array(
          'type'      => 'submit',
          'value'     => t('Save'),
          'callback'  => function($f) {
            return Clydia::Instance()->user->ChangeOwnEmail($f->Value('acronym'), $f->Value('mail'));
          }
        ),
      )
    );

    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', t('The email adress could not be saved.'));
      $this->RedirectToControllerMethod();
    }
    else if ($status === true) {
      $this->AddMessage('success', t('Saved email adress.'));
      $this->RedirectToControllerMethod();
    }

    $data = array(
      'header'   => $this->LoadView('header.tpl.php'),
      'user'     => $this->user,
      'navbar'   => $this->CreateMenu('navbar-ucp'),
      'form'     => $form->GetHTML(),
    );

    $this->breadcrumb[] = array('label' => t('Change password'), 'url' => $this->CreateUrlToControllerMethod());

    $this->views->SetTitle(t('Change password'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddClassToRegion('custom', 'ucp')
                ->AddIncludeToRegion('custom', $this->loadView('email.tpl.php'), $data);
  }


  /**
   * Display information of what groups a user belongs to.
   */
  public function Groups() {
    $if = new CInterceptionFilter();
    $if->IsRegularUserOrForbidden();

    $data = array(
      'header'  => $this->LoadView('header.tpl.php'),
      'user'    => $this->user,
      'navbar'  => $this->CreateMenu('navbar-ucp'),
    );

    $this->breadcrumb[] = array('label' => t('Groups'), 'url' => $this->CreateUrlToControllerMethod());

    $this->views->SetTitle(t('Groups'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddClassToRegion('custom', 'ucp')
                ->AddIncludeToRegion('custom', $this->loadView('groups.tpl.php'), $data);
  }
  


  /**
   * View users content.
   */
  public function Content() {
    $if = new CInterceptionFilter();
    $if->IsRegularUserOrForbidden();

    $content = new CMContent();
    $data = array(
      'header'  => $this->LoadView('header.tpl.php'),
      'user'    => $this->user,
      'navbar'  => $this->CreateMenu('navbar-ucp'),
      'content' => $content->ListAll(),
    );

    $this->breadcrumb[] = array('label' => t('Content'), 'url' => $this->CreateUrlToControllerMethod());

    $this->views->SetTitle(t('Groups'))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddClassToRegion('custom', 'ucp')
                ->AddIncludeToRegion('custom', $this->loadView('content.tpl.php'), $data);
  }
  


  
} 
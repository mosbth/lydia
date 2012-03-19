<?php
/**
 * A user controller  to manage login and view edit the user profile.
 * 
 * @package LydiaCore
 */
class CCUser extends CObject implements IController {


  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }


  /**
   * Show profile information of the user.
   */
  public function Index() {
    $this->views->SetTitle('User Controller');
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
      'is_authenticated'=>$this->user->IsAuthenticated(), 
      'user'=>$this->user->GetProfile(),
    ));
  }
  

  /**
   * View and edit user profile.
   */
  public function Profile() {
    $this->views->SetTitle('User Profile');
    $this->views->AddInclude(__DIR__ . '/profile.tpl.php', array(
      'is_authenticated'=>$this->user->IsAuthenticated(), 
      'user'=>$this->user->GetProfile(),
    ));
  }
  

  /**
   * Authenticate and login a user.
   */
  public function Login($akronymOrEmail=null, $password=null) {
    if($akronymOrEmail && $password) {
      $this->user->Login($akronymOrEmail, $password);
      $this->RedirectToController('profile');
    }
    $this->views->SetTitle('Login');
    $this->views->AddInclude(__DIR__ . '/login.tpl.php');
  }
  

  /**
   * Logout a user.
   */
  public function Logout() {
    $this->user->Logout();
    $this->RedirectToController();
  }
  

  /**
   * Init the user database.
   */
  public function Init() {
    $this->user->Init();
    $this->RedirectToController();
  }
  

} 
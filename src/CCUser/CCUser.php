<?php
/**
 * A user controller  to manage login and view edit the user profile.
 * 
 * @package LydiaCore
 */
class CCUser extends CObject implements IController {

  private $userModel;
  

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->userModel = new CMUser();
  }


  /**
   * Show profile information of the user.
   */
  public function Index() {
    $this->views->SetTitle('User Profile');
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
      'is_authenticated'=>$this->userModel->IsAuthenticated(), 
      'user'=>$this->userModel->GetUserProfile(),
    ));
  }
  

  /**
   * Authenticate and login a user.
   */
  public function Login($akronymOrEmail=null, $password=null) {
    $this->userModel->Login($akronymOrEmail, $password);
    $this->RedirectToController();
  }
  

  /**
   * Authenticate and login a user.
   */
  public function Logout() {
    $this->userModel->Logout();
    $this->RedirectToController();
  }
  

  /**
   * Init the user database.
   */
  public function Init() {
    $this->userModel->Init();
    $this->RedirectToController();
  }
  

} 
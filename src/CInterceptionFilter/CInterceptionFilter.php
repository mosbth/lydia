<?php
/**
 * Interception filter to check if conditions are fulfilled.
 * 
 * @package LydiaCore
 */
class CInterceptionFilter extends CObject implements ISingleton {

  /**
   * Members
   */
  private static $instance = null;


 /**
   * Constructor
   */
  public function __construct() { parent::__construct(); }


  /**
   * Singleton pattern. Get the instance of the latest created object or create a new one. 
   *
   * @return CLydia The instance of this class.
   */
  public static function Instance() {
    return is_null(self::$instance) ? self::$instance = new static : self::$instance;
  }


  /**
   * Check if user is logged in and has administrator role, redirect to login-page or
   * display forbidden depending on privilegies.
   *
   * @return CInterceptionFilter to allow chaining.
   */
  public function AuthenticatedOrLogin() {
    if(!$this->user->IsAuthenticated()) {
      $this->session->SetFlash('redirect_on_success', $this->request->request);
      $this->RedirectTo('user/login');
    }
    return $this;
  }
  
  
  /**
   * Check if user is logged in and has administrator role, redirect to login-page or
   * display forbidden depending on privilegies.
   *
   * @param string $msg to display.
   * @return CInterceptionFilter to allow chaining.
   */
  public function AdminOrForbidden($msg=null) {
    $msg = isset($msg) ? $msg : t('You need admin-privileges to access this content.');
    if(!$this->user->IsAdmin()) {
      $this->ShowErrorPage(403, $msg);
    }
    return $this;
  }
  
  
  /**
   * Check if user is owner of content, or has administrator role else redirect to forbidden.
   *
   * @param CMContent $content to display.
   * @return CInterceptionFilter to allow chaining.
   */
  public function OwnerAdminOrForbidden($content) {
    if($this->user->IsAdmin() || $content->CurrentUserIsOwner()) {
      return $this;
    }
    $msg = t('You do not have privileges to access this content.');
    return $this->ShowErrorPage(403, $msg);
  }
  
  
  /**
   * Check if secret matches in config.php. This enables remote management of some features
   * without being loggedin.
   *
   * @param string $key secret key to match in config.php.
   * @return CInterceptionFilter to allow chaining.
   */
  public function MatchSecretKey($secret=null) {
    if($this->config['secret_key'] != $secret) {
      $this->ShowErrorPage(403, t('You need admin-privileges to access this page.'));
    }
    return $this;
  }
  
  
  /**
   * Check if user acronym is same as current session acronym.
   *
   * @param string $acronym
   * @return boolean true or false.
   */
  public function SessionUserMatches($acronym) {
    $user = $this->session->GetAuthenticatedUser();
    if($acronym == $user['acronym']) {
      return true;
    }
    $this->AddMessage('error', t('User conflict between value and session.'));
    return false;
  }


  /**
   * Allow creating new users or display forbidden.
   *
   * @return CInterceptionFilter to allow chaining.
   */
  public function CreateNewUserOrForbidden() {
    if(!$this->config['create_new_users']) {
      $this->ShowErrorPage(403, t('Create new user is disabled by site owner.'));
    }
    return $this;
  }


  /**
   * Is regular user or display forbidden.
   *
   * @return CInterceptionFilter to allow chaining.
   */
  public function IsRegularUserOrForbidden() {
    if(!$this->user->IsUser()) {
      $this->ShowErrorPage(403, t('You are just visiting this site and have no profile.'));
    }
    return $this;
  }


  /**
   * Display forbidden for all anonomous users.
   *
   * @return CInterceptionFilter to allow chaining.
   */
  public function AnonomousToForbidden() {
    if($this->user->IsAnonomous()) {
      $this->ShowErrorPage(403, t('You need to login to access this content.'));
    }
    return $this;
  }



}
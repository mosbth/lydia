<?php
/**
 * Interception filter to check if conditions are fulfilled.
 * 
 * @package LydiaCore
 */
class CInterceptionFilter extends CObject {

  /**
   * Constructor
   */
  public function __construct() { parent::__construct(); }


  /**
   * Check if user is logged in and has administrator role, redirect to login-page or
   * display forbidden depending on privilegies.
   *
   * @returns CInterceptionFilter to allow chaining.
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
   * @returns CInterceptionFilter to allow chaining.
   */
  public function AdminOrForbidden() {
    if(!$this->user->IsAdmin()) {
      $this->ShowErrorPage(403, t('You need admin-privileges to access this page.'));
    }
    return $this;
  }
  
  
  /**
   * Check if secret matches in config.php. This enables remote management of some features
   * without being loggedin.
   *
   * @param string $key secret key to match in config.php.
   * @returns CInterceptionFilter to allow chaining.
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
   * @returns boolean true or false.
   */
  public function SessionUser($acronym) {
    $user = $this->session->GetAuthenticatedUser();
    if($acronym == $user['acronym']) {
      return true;
    }
    $this->AddMessage('error', t('User conflict between value and session.'));
    return false;
  }
  
  
}
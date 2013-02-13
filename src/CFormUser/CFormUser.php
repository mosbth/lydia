<?php
/**
 * Forms for the user control panel and login.
 * 
 * @package LydiaCore
 */
class CFormUser extends CForm {

  /**
   * Constructor
   */
  public function __construct() { parent::__construct(); }
  

  /**
   * Check if user acronym in form matches user acronym in session.
   *
   * @param string $acronym the acronym from the form.
   */
  public function MatchAcronymToSession($acronym) {
    return CInterceptionFilter::Instance()->SessionUserMatches($acronym);
  }
  

  /**
   * Create the login form.
   *
   * @param CUser $user the user object.
   */
  public function CreateLogin($user) {
    $this->AddElement(new CFormElementText('acronym'))
         ->AddElement(new CFormElementPassword('password'))
         ->AddElement(new CFormElementSubmit('login', array('callback'=>array($this, 'DoLogin'), 'callback-args'=>array($user))));

 /*   $this->SetValidation('acronym', array('not_empty'))
         ->SetValidation('password', array('not_empty'));*/
  }
  

  /**
   * Try to login.
   *
   * @param CForm $form the current form.
   * @param CUser $user the user object.
   */
  public function DoLogin($form, $user) {
    return $user->Login($form['acronym']['value'], $form['password']['value']);
  }
  

  /**
   * Create the profile form.
   *
   * @param CUser $user the user object.
   */
  public function CreateProfile($user) {
    $this->AddElement(new CFormElementHidden('acronym', array('readonly'=>true, 'value'=>$user['acronym'])))
         ->AddElement(new CFormElementText('name', array('value'=>$user['name'], 'required'=>true)))
         ->AddElement(new CFormElementText('email', array('value'=>$user['email'], 'required'=>true)))
         ->AddElement(new CFormElementSubmit('save', array('callback'=>array($this, 'DoProfileSave'), 'callback-args'=>array($user))));
         
    $this->SetValidation('name', array('not_empty'))
         ->SetValidation('email', array('not_empty'));
  }
  

  /**
   * Save updates to profile information.
   *
   * @param CForm $form the current form.
   * @param CUser $user the user object.
   */
  public function DoProfileSave($form, $user) {
    if(!$this->MatchAcronymToSession($form['acronym']['value'])) { return false; }
    $user['name'] = $form['name']['value'];
    $user['email'] = $form['email']['value'];
    return $user->Save();
  }
  

  /**
   * Change password form.
   *
   * @param CUser $user the user object.
   */
/*  public function CreateChangePassword($user) {
    $this->AddElement(new CFormElementHidden('acronym', array('value'=>$user['acronym'])))
         ->AddElement(new CFormElementPassword('password1', array('label'=>'Current password:')))
         ->AddElement(new CFormElementPassword('password2', array('label'=>'New password:')))
         ->AddElement(new CFormElementPassword('password3', array('label'=>'New password again:')))
         ->AddElement(new CFormElementSubmit('change_password', array('callback'=>array($this, 'DoChangePassword'), 'callback-args'=>array($user))));
         
    $this->SetValidation('password1', array('not_empty'))
         ->SetValidation('password2', array('not_empty'))
         ->SetValidation('password3', array('not_empty', 'match'=>'password2'));
  }
  */

  /**
   * Change the password.
   *
   * @param CForm $form the current form.
   * @param CUser $user the user object.
   */
  public function DoChangePassword($form, $user) {
    if(!$this->MatchAcronymToSession($form['acronym']['value'])) { return false; }  
    if(empty($form['password1']['value']) || empty($form['password1']['value']) || empty($form['password1']['value']) ||
      ($form['password2']['value'] != $form['password3']['value'])) {
      return false;
    }
    return $user->ChangePassword($form['password1']['value'], $form['password2']['value']);
  }
  

  /**
   * Form to create a new user.
   *
   * @param CUser $user the user object.   
   */
  public function CreateUserCreate($user) {
    parent::__construct();
    $this->AddElement(new CFormElementText('acronym', array('required'=>true)))
         ->AddElement(new CFormElementPassword('password1', array('required'=>true, 'label'=>'Password:')))
         ->AddElement(new CFormElementPassword('password2', array('required'=>true, 'label'=>'Password again:')))
         ->AddElement(new CFormElementText('name', array('required'=>true)))
         ->AddElement(new CFormElementText('email', array('required'=>true)))
         ->AddElement(new CFormElementSubmit('create', array('callback'=>array($this, 'DoCreate'), 'callback-args'=>array($user))));
         
    $this->SetValidation('acronym', array('not_empty'))
         ->SetValidation('password1', array('not_empty'))
         ->SetValidation('password2', array('not_empty', 'match'=>'password1'))
         ->SetValidation('name', array('not_empty'))
         ->SetValidation('email', array('not_empty'));
  }
  

  /**
   * Perform a creation of a user as callback on a submitted form.
   *
   * @param CForm $form the current form.
   * @param CUser $user the user object.
   */
  public function DoCreate($form, $user) {
    if($form['password1']['value'] != $form['password1']['value'] || 
       empty($form['password1']['value']) || empty($form['password2']['value']) ||
       empty($form['acronym']['value']) || empty($form['name']['value']) || empty($form['email']['value']) 
      ) {
      return false;
    } 
    if($user->Create($form['acronym']['value'], $form['password1']['value'], $form['name']['value'], $form['email']['value'])) {
      return $user->Login($form['acronym']['value'], $form['password1']['value']);
    }
    return false;
  }
  

}



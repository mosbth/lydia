<?php
/**
 * Forms for the managing modules and Lydia.
 * 
 * @package LydiaCore
 */
class CFormModules extends CForm {

  /**
   * Constructor
   */
  public function __construct() { parent::__construct(); }
  

  /**
   * Form to process SQL.
   *
   * @param CMModules $mananger the module manager object.
   */
  public function CreateExecuteSQL($manager) {
    $this->AddElement(new CFormElementTextarea('sql', array('label'=>'SQL:', 'class'=>'code nowrap', 'remember'=>true)))
         ->AddElement(new CFormElementSubmit('execute', array('callback'=>array($this, 'DoProcessSQL'), 'callback-args'=>array($manager))));

    $this->SetValidation('sql', array('not_empty'));
  }
  

  /**
   * Try to login.
   *
   * @param CForm $form the current form.
   * @param CMModules $mananger the module manager object.
   */
  public function DoProcessSQL($form, $manager) {
    return $manager->DoSQL($form['sql']['value']);
  }
  

}
<?php
// Include CForm
include('../CForm.php');


/**
 * Create a class for a contact-form with name, email and phonenumber.
 */
class CFormContact extends CForm {


  /** Create all form elements and validation rules in the constructor.
   *
   */
  public function __construct() {
    parent::__construct();
    
    $this->AddElement(new CFormElementText('name', array('label'=>'Name of contact person:', 'required'=>true)))
         ->AddElement(new CFormElementText('email', array('required'=>true)))
         ->AddElement(new CFormElementText('phone', array('required'=>true)))
         ->AddElement(new CFormElementSubmit('submit', array('callback'=>array($this, 'DoSubmit'))))
         ->AddElement(new CFormElementSubmit('submit-fail', array('callback'=>array($this, 'DoSubmitFail'))));
         
    $this->SetValidation('name', array('not_empty'))
         ->SetValidation('email', array('not_empty', 'email_adress'))
         ->SetValidation('phone', array('not_empty', 'numeric'));
  }


  /**
   * Callback for submitted forms, will always fail
   */
  protected function DoSubmitFail() {
    $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
    return false;
  }


  /**
   * Callback for submitted forms
   */
  protected function DoSubmit() {
    $this->AddOutput("<p><i>DoSubmit(): Form was submitted. Do stuff (save to database) and return true (success) or false (failed processing form)</i></p>");
    $this->AddOutput("<p><b>Name: " . $this->Value('name') . "</b></p>");
    $this->AddOutput("<p><b>Email: " . $this->Value('email') . "</b></p>");
    $this->AddOutput("<p><b>Phone: " . $this->Value('phone') . "</b></p>");
    $this->saveInSession = true;
    return true;
  }

}


// -----------------------------------------------------------------------
//
// Use the form and check it status.
//
session_name('cform_example');
session_start();
$form = new CFormContact();


// Check the status of the form
$status = $form->Check();

// What to do if the form was submitted?
if($status === true) {
  $form->AddOUtput("<p><i>Form was submitted and the callback method returned true.</i></p>");
  header("Location: " . $_SERVER['PHP_SELF']);
}

// What to do when form could not be processed?
else if($status === false){
  $form->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
  header("Location: " . $_SERVER['PHP_SELF']);
}

?>


<!doctype html>
<meta charset=utf8>
<title>CForm Example: Basic example on how to use CForm (part 6)</title>
<h1>CForm Example: Basic example on how to use CForm (part 6)</h1>
<?=$form->GetHTML()?>


<p><code>$_POST</code> <?php if(empty($_POST)) {echo '<i>is empty.</i>';} else {echo '<i>contains:</i><pre>' . print_r($_POST, 1) . '</pre>';} ?></p>

<?php $footer = "../../template/footer_mos.php"; if(is_file($footer)) include($footer) ?>

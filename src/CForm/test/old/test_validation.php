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

    $tests = array(
      'not_empty' => 'not_empty',
      'numeric' => 'numeric',
      'mail_address' => 'mail_address',
    );
    $testKeys = array_keys($tests);

    $this->AddElement(new CFormElementText('Enter-a-value', array()))
         ->AddElement(new CFormElementCheckboxMultiple('items', array('description'=>'Choose the validation rules to use.', 'values'=>$testKeys, 'checked'=>null)))
         ->AddElement(new CFormElementSubmit('submit', array('callback'=>array($this, 'DoSubmit'))));
  
    if(!empty($_POST['items'])) {
      $validation = array();
      foreach($_POST['items'] as $val) {
        $validation[] = $tests[$val];
      }
      //echo "<pre>" . print_r($validation, true) . "</pre>";
      $this->SetValidation('Enter-a-value', $validation);
    }
  }

  /**
   * Callback for submitted forms, will always fail
   */
  protected function DoSubmitFail() {
    echo "<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>";
    return false;
  }


  /**
   * Callback for submitted forms
   */
  protected function DoSubmit() {
    echo "<p><i>DoSubmit(): Form was submitted. Do stuff (save to database) and return true (success) or false (failed processing form)</i></p>";
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
  echo "<p><i>Form was submitted and the callback method returned true. I should redirect to a page to avoid issues with reloading posted form.</i></p>";
}

// What to do when form could not be processed?
else if($status === false){
  echo "<p><i>Form was submitted and the Check() method returned false. I should redirect to a page to avoid issues with reloading posted form.</i></p>";
}

$title = "Lydia CForm: Validate text element";
?>


<!doctype html>
<meta charset=utf-8>
<title><?=$title?></title>
<h1><?=$title?></h1>
<?=$form->GetHTML()?>

<p><code>$_POST</code> <?php if(empty($_POST)) {echo '<i>is empty.</i>';} else {echo '<i>contains:</i><pre>' . print_r($_POST, 1) . '</pre>';} ?></p>

<?php include("../template/footer_mos.php") ?>

<?php
// Include CForm
include('../CForm.php');

/*
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


      'fail' => array('message' => 'Will always fail.', 'test' => 'return false;'),
      'pass' => array('message' => 'Will always pass.', 'test' => 'return true;'),
      'not_empty' => array('message' => 'Can not be empty.', 'test' => 'return $value != "";'),
      'not_equal' => array('message' => 'Value not valid.', 'test' => 'return $value != $arg;'),
      'numeric' => array('message' => 'Must be numeric.', 'test' => 'return is_numeric($value);'),
      'email_adress' => array('message' => 'Must be an email adress.', 'test' => function($value) { return preg_match('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $value) === 1; } ),
      'match' => array('message' => 'The field does not match.', 'test' => 'return $value == $form[$arg]["value"] ;'),
      'must_accept' => array('message' => 'You must accept this.', 'test' => 'return $checked;'),
      'custom_test' => true,


*/

// -----------------------------------------------------------------------
//
// Use the form and check it status.
//
session_name('cform_example');
session_start();

// Get selected validation rules
$rules = array('not_empty', 'numeric', 'email_adress');
$validation = array();
if(!empty($_POST['tests'])) {
  foreach($_POST['tests'] as $val) {
    if(in_array($val, $rules)) {
      $validation[] = $val;
    }
  }
}

$form = new CForm(array(), array(
    'enter-a-value' => array(
      'type'        => 'text',
    ),        
    'tests' => array(
      'type'        => 'checkbox-multiple',
      'description' => 'Choose the validation rules to use.',
      'values'      => $rules,
    ),
    'submit' => array(
      'type'      => 'submit',
      'callback'  => function($form) {
        $form->AddOutput("<p><i>DoSubmit(): Nothing to do.</i></p>");
        //$form->AddOutput("<pre>" . print_r($_POST, 1) . "</pre>");
        $form->saveInSession = true;
        return true;
      }
    ),
    'submit-fail' => array(
      'type'      => 'submit',
      'callback'  => function($form) {
        $form->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
      }
    ),
  )
);

// Set the active validation rules
$form->SetValidation('enter-a-value', $validation);

// Check the status of the form
$status = $form->Check();

// What to do if the form was submitted?
if($status === true) {
  $form->AddOutput("<p><i>Form was submitted and the callback method returned true.</i></p>");
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
<title>CForm Example: Try validation methods</title>
<h1>CForm Example: Try validation methods</h1>
<?=$form->GetHTML()?>

<?php $footer = "../../template/footer_mos.php"; if(is_file($footer)) include($footer) ?>

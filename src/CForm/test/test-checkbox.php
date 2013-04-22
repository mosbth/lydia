<?php
// Include CForm
include('../CForm.php');

/*
    $this->AddElement(new CFormElementCheckbox('accept_mail', array('label'=>'It´s great if you send me product information by mail.', 'checked'=>false)))
         ->AddElement(new CFormElementCheckbox('accept_phone', array('label'=>'You may call me to try and sell stuff.', 'checked'=>true)))
         ->AddElement(new CFormElementCheckbox('accept_agreement', array('label'=>'You must accept the <a href=http://opensource.org/licenses/GPL-3.0>license agreement</a>.', 'required'=>true)))

    $this->SetValidation('accept_agreement', array('must_accept'));
*/

// -----------------------------------------------------------------------
//
// Use the form and check it status.
//
session_name('cform_example');
session_start();
$form = new CForm(array(), array(
    'accept_mail' => array(
      'type'        => 'checkbox',
      'label'       => 'It´s great if you send me product information by mail.',
      'checked'     => false,
    ),        
    'accept_phone' => array(
      'type'        => 'checkbox',
      'label'       => 'You may call me to try and sell stuff.',
      'checked'     => true,
    ),        
    'accept_agreement' => array(
      'type'        => 'checkbox',
      'label'       => 'You must accept the <a href=http://opensource.org/licenses/GPL-3.0>license agreement</a>.',
      'required'    => true,
      'validation'  => array('must_accept'),
    ),        
    'submit' => array(
      'type'      => 'submit',
      'callback'  => function($form) {
        $form->AddOutput("<p><i>DoSubmit(): Form was submitted. Do stuff (save to database) and return true (success) or false (failed processing form)</i></p>");
        $form->AddOutput("<pre>" . print_r($_POST, 1) . "</pre>");
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
<title>CForm Example: Using checkboxes</title>
<h1>CForm Example: Using checkboxes</h1>
<?=$form->GetHTML()?>

<?php $footer = "../../template/footer_mos.php"; if(is_file($footer)) include($footer) ?>

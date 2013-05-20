<?php
// Include CForm
include('../CForm.php');

/*
    $items = Array('tomato', 'potato', 'apple', 'pear', 'banana');
    $shoppingcart = Array('potato', 'pear');

    $this->AddElement(new CFormElementCheckboxMultiple('items', array('values'=>$items, 'checked'=>$shoppingcart)));
    $this->AddElement(new CFormElementSubmit('submit', array('callback'=>array($this, 'DoSubmit'))));
*/

// -----------------------------------------------------------------------
//
// Use the form and check it status.
//
session_name('cform_example');
session_start();
$form = new CForm(array(), array(
    'items' => array(
      'type'        => 'checkbox-multiple',
      'values'      => array('tomato', 'potato', 'apple', 'pear', 'banana'),
      'checked'     => array('potato', 'pear'),
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
<title>CForm Example: Using multiple checkboxes</title>
<h1>CForm Example: Using multiple checkboxes</h1>
<?=$form->GetHTML()?>

<?php $footer = "../../template/footer_mos.php"; if(is_file($footer)) include($footer) ?>

<?php
// Include CForm
include('../CForm.php');

// Adapted from Java code at http://www.merriampark.com/anatomycc.htm
// by Andy Frey, onesandzeros.biz
// Checks for valid credit card number using Luhn algorithm
// Source from: http://onesandzeros.biz/notebook/ccvalidation.php
// 
// Try the following numbers, they should be valid according to the check:
// 4408 0412 3456 7893
// 4417 1234 5678 9113
//
function isValidCCNumber( $ccNum ) {
    $digitsOnly = "";
    // Filter out non-digit characters
    for( $i = 0; $i < strlen( $ccNum ); $i++ ) {
        if( is_numeric( substr( $ccNum, $i, 1 ) ) ) {
            $digitsOnly .= substr( $ccNum, $i, 1 );
        }
    }
    // Perform Luhn check
    $sum = 0;
    $digit = 0;
    $addend = 0;
    $timesTwo = false;
    for( $i = strlen( $digitsOnly ) - 1; $i >= 0; $i-- ) {
        $digit = substr( $digitsOnly, $i, 1 );
        if( $timesTwo ) {
            $addend = $digit * 2;
            if( $addend > 9 ) {
                $addend -= 9;
            }
        } else {
            $addend = $digit;
        }
        $sum += $addend;
        $timesTwo = !$timesTwo;
    }
    return $sum % 10 == 0;
}

/*
MII Digit Value Issuer Category
0 ISO/TC 68 and other industry assignments
1 Airlines
2 Airlines and other industry assignments
3 Travel and entertainment
4 Banking and financial
5 Banking and financial
6 Merchandizing and banking
7 Petroleum
8 Telecommunications and other industry assignments
9 National assignment
*/


/*
Issuer  Identifier  Card Number Length
Diner's Club/Carte Blanche  300xxx-305xxx,
36xxxx, 38xxxx  14
American Express  34xxxx, 37xxxx  15
VISA  4xxxxx  13, 16
MasterCard  51xxxx-55xxxx   16
Discover  6011xx  16
*/



// -----------------------------------------------------------------------
//
// Use the form and check it status.
//
session_name('cform_example');
session_start();
$currentYear = date('Y');
$elements = array(
  'payment' => array(
    'type' => 'hidden',
    'value' => 10
  ),
  'name' => array(
    'type' => 'text',
    'label' => 'Name on credit card:',
    'required' => true,
    'autofocus' => true,
    'validation' => array('not_empty')
  ),
  'address' => array(
    'type' => 'text',
    'required' => true,
    'validation' => array('not_empty')
  ),
  'zip' => array(
    'type' => 'text',
    'required' => true,
    'validation' => array('not_empty')
  ),
  'city' => array(
    'type' => 'text',
    'required' => true,
    'validation' => array('not_empty')
  ),
  'country' => array(
    'type' => 'select',
    'options' => array(
      'default' => 'Select a country...',
      'no' => 'Norway',
      'se' => 'Sweden',
    ),
    'validation' => array('not_empty', 'not_equal' => 'default')
  ),
  'cctype' => array(
    'type' => 'select',
    'label' => 'Credit card type:',
    'options' => array(
      'default' => 'Select a credit card type...',
      'visa' => 'VISA',
      'mastercard' => 'Mastercard',
      'eurocard' => 'Eurocard',
      'amex' => 'American Express',
    ),
    'validation' => array('not_empty', 'not_equal' => 'default')
  ),
  'ccnumber' => array(
    'type' => 'text',
    'label' => 'Credit card number:',
    'validation' => array('not_empty', 'custom_test' => array('message' => 'Credit card number is not valid, try using 4408 0412 3456 7893 or 4417 1234 5678 9113 :-).', 'test' => 'isValidCCNumber')),
  ),
  'expmonth' => array(
    'type' => 'select',
    'label' => 'Expiration month:',
    'options' => array(
      'default' => 'Select credit card expiration month...',
      '01' => 'January',
      '02' => 'February',
      '03' => 'March',
      '04' => 'April',
      '05' => 'May',
      '06' => 'June',
      '07' => 'July',
      '08' => 'August',
      '09' => 'September',
      '10' => 'October',
      '11' => 'November',
      '12' => 'December',
    ),
    'validation' => array('not_empty', 'not_equal' => 'default')
  ),
  'expyear' => array(
    'type' => 'select',
    'label' => 'Expiration year:',
    'options' => array(
      'default' => 'Select credit card expiration year...',
      $currentYear    => $currentYear,
      ++$currentYear  => $currentYear,
      ++$currentYear  => $currentYear,
      ++$currentYear  => $currentYear,
      ++$currentYear  => $currentYear,
      ++$currentYear  => $currentYear,
    ),
    'validation' => array('not_empty', 'not_equal' => 'default')
  ),
  'cvc' => array(
    'type' => 'text',
    'label' => 'CVC:',
    'required' => true,
    'validation' => array('not_empty', 'numeric')
  ),
  'doPay' => array(
    'type' => 'submit',
    'value' => 'Perform payment',
    'callback' => function($form) {
      // Taking some money from the creditcard.
      return true;
    }
  ),
);

$form = new CForm(array(), $elements);

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

$columns = isset($_GET['cols']) && $_GET['cols'] == 2 ? 2 : 1;
?>


<!doctype html>
<meta charset=utf8>
<title>CForm Example: Creditcard checkout with two column layout</title>
<style>
.cform-columns-2 .cform-column-1 { float: left; width: 50%; }
.cform-columns-2 .cform-column-2 { float: left; width: 50%; }
.cform-columns-2 .cform-buttonbar { clear: both; background-color: #ccc; padding: 1em; border: 1px solid #aaa; }
.cform-columns-2 .cform-buttonbar p { margin-bottom: 0; }
</style>
<h1>CForm Example: Creditcard checkout with two column layout</h1>
<p>View this form in a <a href='?cols=2'>two-column layout</a> or in a <a href='?'>standard layout</a>.</p>
<?=$form->GetHTML(array('columns' => $columns))?>

<?php $footer = "../../template/footer_mos.php"; if(is_file($footer)) include($footer) ?>

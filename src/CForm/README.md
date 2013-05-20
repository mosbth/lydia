CForm a PHP class for creating, rendering and validating HTML forms.
==================================

There is an article written on CForm, in swedish though. 
* http://dbwebb.se/kunskap/cform-en-php-klass-for-att-skapa-presentera-och-validera-html-formular


By Mikael Roos (me@mikaelroos.se)


License
----------------------------------

This software has dual license, MIT LICENSE and GPL VERSION 3.



Form elements
----------------------------------

The class `CForm` uses `CFormElements`.

The following form elements are supported:

    class CFormElementText extends CFormElement {
    class CFormElementTextarea extends CFormElement {
    class CFormElementHidden extends CFormElement {
    class CFormElementPassword extends CFormElement {
    class CFormElementCheckbox extends CFormElement {
    class CFormElementCheckboxMultiple extends CFormElement {
    class CFormElementSubmit extends CFormElement {



Validation rules
----------------------------------

The following validation rules are supported:

    'fail' => array('message' => 'Will always fail.', 'test' => 'return false;'),
    'pass' => array('message' => 'Will always pass.', 'test' => 'return true;'),
    'not_empty' => array('message' => 'Can not be empty.', 'test' => 'return $value != "";'),
    'numeric' => array('message' => 'Must be numeric.', 'test' => 'return is_numeric($value);'),
    'mail_address' => array('message' => 'Must be an emailaddress.', 'test' => function($value) { return preg_match('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $value) === 1; } ),
    'match' => array('message' => 'The field does not match.', 'test' => 'return $value == $form[$arg]["value"] ;'),
    'must_accept' => array('message' => 'You must accept this.', 'test' => 'return isset($value);'),

The validation rule for 'match' is useful when changing password and one field should match another field in the form.
The validation rule for 'must_accept' is useful when the user must accept a license agreement by clicking a checkbox.



Todo
----------------------------------

* Layout form elements in grid.
* Style form using LESS and CSS.
* Support all form elements.
* Make page displaying how all form elements look like in different styles.
* Support more validation rules.
* Make example on how you use the validation rules.
* Integrate/support with client side validation through js/ajax.
* Support saving partial data of form through js/ajax.
* Check that the form is valid by storing key in session and hidden field and match those.


History
----------------------------------

2013-04-22 v0.9.0
* First tag as baseline when moving CForm from Lydia to own repository.

2012-11-26:
* Added validation rule for email address `email_address`. Rewrote `CFormElement::Validate()` to accept anonomous function as validation rule.

2012-11-14:
* Added `CFormElementCheckboxMultiple`.
* Added code example for checkbox-multiple:  
    http://dbwebb.se/kod-exempel/cform/test_checkbox_multiple.php
    http://dbwebb.se/kod-exempel/cform/test_checkbox1.php (same example but not true multiple choice)

2012-11-13:
* Added `CFormElementCheckbox` and validation rule `must_accept`.
* Added code example for checkboxes:  
    http://dbwebb.se/kod-exempel/cform/test_checkbox.php


2012-10-05: 
* Updated this readme-file and reworked the tutorial at  
  http://dbwebb.se/kunskap/anvand-lydias-formularklass-cform-for-formularhantering

* Updated the code-example at:  
  http://dbwebb.se/kod-exempel/cform/


 .   
..:  Copyright 2012-2013 by Mikael Roos (me@mikaelroos.se)

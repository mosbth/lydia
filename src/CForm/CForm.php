<?php
/**
 * A utility class to easy creating and handling of forms
 * 
 * @package LydiaCore
 */
class CFormElement implements ArrayAccess{

  /**
   * Properties
   */
  public $attributes;
  public $characterEncoding;
  

  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    $this->attributes = $attributes;    
    $this['name'] = $name;
    if(is_callable('CLydia::Instance()')) {
      $this->characterEncoding = CLydia::Instance()->config['character_encoding'];
    } else {
      $this->characterEncoding = 'UTF-8';
    }
  }
  
  
  /**
   * Implementing ArrayAccess for this->attributes
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->attributes[] = $value; } else { $this->attributes[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->attributes[$offset]); }
  public function offsetUnset($offset) { unset($this->attributes[$offset]); }
  public function offsetGet($offset) { return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null; }


  /**
   * Get HTML code for a element. 
   *
   * @returns HTML code for the element.
   */
  public function GetHTML() {
    $id = isset($this['id']) ? $this['id'] : 'form-element-' . $this['name'];
    $class = isset($this['class']) ? " {$this['class']}" : null;
    $validates = (isset($this['validation-pass']) && $this['validation-pass'] === false) ? ' validation-failed' : null;
    $class = (isset($class) || isset($validates)) ? " class='{$class}{$validates}'" : null;
    $name = " name='{$this['name']}'";
    $label = isset($this['label']) ? ($this['label'] . (isset($this['required']) && $this['required'] ? "<span class='form-element-required'>*</span>" : null)) : null;
    $autofocus = isset($this['autofocus']) && $this['autofocus'] ? " autofocus='autofocus'" : null;    
    $readonly = isset($this['readonly']) && $this['readonly'] ? " readonly='readonly'" : null;    
    $checked = isset($this['checked']) && $this['checked'] ? " checked='checked'" : null;    
    $type   = isset($this['type']) ? " type='{$this['type']}'" : null;
    $onlyValue  = isset($this['value']) ? htmlentities($this['value'], ENT_QUOTES, $this->characterEncoding) : null;
    $value  = isset($this['value']) ? " value='{$onlyValue}'" : null;

    $messages = null;
    if(isset($this['validation-messages'])) {
      $message = null;
      foreach($this['validation-messages'] as $val) {
        $message .= "<li>{$val}</li>\n";
      }
      $messages = "<ul class='validation-message'>\n{$message}</ul>\n";
    }
    
    if($type && $this['type'] == 'submit') {
      return "<span><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} /></span>\n";
    } else if($type && $this['type'] == 'textarea') {
      return "<p><label for='$id'>$label</label><br><textarea id='$id'{$type}{$class}{$name}{$autofocus}{$readonly}>{$onlyValue}</textarea></p>\n"; 
    } else if($type && $this['type'] == 'hidden') {
      return "<input id='$id'{$type}{$class}{$name}{$value} />\n"; 
    } else if($type && $this['type'] == 'checkbox') {
      return "<p><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly}{$checked} /><label for='$id'>$label</label>{$messages}</p>\n"; 
    } else {
      return "<p><label for='$id'>$label</label><br><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} />{$messages}</p>\n";        
    }
  }


  /**
   * Validate the form element value according a ruleset.
   *
   * @param array $rules validation rules.
   * @param CForm $form the parent form.
   * returns boolean true if all rules pass, else false.
   */
  public function Validate($rules, $form) {
    $tests = array(
      'fail' => array('message' => 'Will always fail.', 'test' => 'return false;'),
      'pass' => array('message' => 'Will always pass.', 'test' => 'return true;'),
      'not_empty' => array('message' => 'Can not be empty.', 'test' => 'return $value != "";'),
      'numeric' => array('message' => 'Must be numeric.', 'test' => 'return is_numeric($value);'),
      'match' => array('message' => 'The field does not match.', 'test' => 'return $value == $form[$arg]["value"] ;'),
      'must_accept' => array('message' => 'You must accept this.', 'test' => 'return $checked;'),
    );
    $pass = true;
    $messages = array();
    $value = $this['value'];
    $checked = $this['checked'];
    foreach($rules as $key => $val) {
      $rule = is_numeric($key) ? $val : $key;
      if(!isset($tests[$rule])) throw new Exception('Validation of form element failed, no such validation rule exists.');
      $arg = is_numeric($key) ? null : $val;
      if(eval($tests[$rule]['test']) === false) {
        $messages[] = $tests[$rule]['message'];
        $pass = false;
      }
    }
    if(!empty($messages)) $this['validation-messages'] = $messages;
    return $pass;
  }


  /**
   * Use the element name as label if label is not set.
   */
  public function UseNameAsDefaultLabel() {
    if(!isset($this['label'])) {
      $this['label'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name']))).':';
    }
  }


  /**
   * Use the element name as value if value is not set.
   */
  public function UseNameAsDefaultValue() {
    if(!isset($this['value'])) {
      $this['value'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name'])));
    }
  }


}


class CFormElementText extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'text';
    $this->UseNameAsDefaultLabel();
  }
}


class CFormElementTextarea extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'textarea';
    $this->UseNameAsDefaultLabel();
  }
}


class CFormElementHidden extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'hidden';
  }
}


class CFormElementPassword extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'password';
    $this->UseNameAsDefaultLabel();
  }
}


class CFormElementCheckbox extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type']     = 'checkbox';
    $this['checked']  = isset($attributes['checked']) ? $attributes['checked'] : false;
    $this['value']    = isset($attributes['value']) ? $attributes['value'] : $name;
  }
}


class CFormElementSubmit extends CFormElement {
  /**
   * Constructor
   *
   * @param string name of the element.
   * @param array attributes to set to the element. Default is an empty array.
   */
  public function __construct($name, $attributes=array()) {
    parent::__construct($name, $attributes);
    $this['type'] = 'submit';
    $this->UseNameAsDefaultValue();
  }
}


class CForm implements ArrayAccess {

  /**
   * Properties
   */
  public $form;     // array with settings for the form
  public $elements; // array with all form elements
  

  /**
   * Constructor
   */
  public function __construct($form=array(), $elements=array()) {
    $this->form = $form;
    $this->elements = $elements;
  }


  /**
   * Implementing ArrayAccess for this->elements
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->elements[] = $value; } else { $this->elements[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->elements[$offset]); }
  public function offsetUnset($offset) { unset($this->elements[$offset]); }
  public function offsetGet($offset) { return isset($this->elements[$offset]) ? $this->elements[$offset] : null; }


  /**
   * Add a form element
   *
   * @param $element CFormElement the formelement to add.
   * @returns $this CForm
   */
  public function AddElement($element) {
    $this[$element['name']] = $element;
    return $this;
  }
  

  /**
   * Set validation to a form element
   *
   * @param $element string the name of the formelement to add validation rules to.
   * @param $rules array of validation rules.
   * @returns $this CForm
   */
  public function SetValidation($element, $rules) {
    $this[$element]['validation'] = $rules;
    return $this;
  }
  

  /**
   * Return HTML for the form or the formdefinition.
   *
   * @param $attributes array with attributes affecting the form output.
   * @returns string with HTML for the form.
   */
  public function GetHTML($attributes=null) {
    if(is_array($attributes)) {
      $this->form = array_merge($this->form, $attributes);
    }
    $id     = isset($this->form['id'])      ? " id='{$this->form['id']}'" : null;
    $class  = isset($this->form['class'])   ? " class='{$this->form['class']}'" : null;
    $name   = isset($this->form['name'])    ? " name='{$this->form['name']}'" : null;
    $action = isset($this->form['action'])  ? " action='{$this->form['action']}'" : null;
    $method = " method='post'";

    if(isset($attributes['start']) && $attributes['start']) {
      return "<form{$id}{$class}{$name}{$action}{$method}>\n";
    }
    
    $elements = $this->GetHTMLForElements();
    $html = <<< EOD
\n<form{$id}{$class}{$name}{$action}{$method}>
<fieldset>
{$elements}
</fieldset>
</form>
EOD;
    return $html;
  }
 

  /**
   * Return HTML for the elements
   */
  public function GetHTMLForElements() {
    $html = null;
    $buttonbar = null;
    foreach($this->elements as $element) {
      // Wrap buttons in buttonbar.
      if(!$buttonbar && $element['type'] == 'submit') {
        $buttonbar = true;
        $html .= '<p>';
      } else if($buttonbar && $element['type'] != 'submit') {
        $buttonbar = false;
        $html .= "</p>\n";
      }
      $html .= $element->GetHTML();
    }
    return $html;
  }
  

  /**
   * Check if a form was submitted and perform validation and call callbacks.
   * The form is stored in the session if validation or callback fails. The page should then be redirected
   * to the original form page, the form will populate from the session and should be rendered again.
   * Form elements may remember their value if 'remember' is set and true.
   *
   * @returns mixed, $callbackStatus if submitted&validates, false if not validate, null if not submitted.
   */
  public function Check() {
    $remember = null;
    $validates = null;
    $callbackStatus = null;
    $values = array();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      unset($_SESSION['form-failed']);
      $validates = true;
      foreach($this->elements as $element) {

        // The form element has a value set
        if(isset($_POST[$element['name']])) {
          $values[$element['name']]['value'] = $element['value'] = $_POST[$element['name']];

          // If the element is a checkbox, set its value of checked.
          if($element['type'] === 'checkbox') {
            $element['checked'] = true;
          }

          if(isset($element['validation'])) {
            $element['validation-pass'] = $element->Validate($element['validation'], $this);
            if($element['validation-pass'] === false) {
              $values[$element['name']] = array('value'=>$element['value'], 'validation-messages'=>$element['validation-messages']);
              $validates = false;
            }
          }
          if(isset($element['remember']) && $element['remember']) {
            $values[$element['name']] = array('value'=>$element['value']);
            $remember = true;
          }
          if(isset($element['callback']) && $validates) {
            if(isset($element['callback-args'])) {
              $callbackStatus = call_user_func_array($element['callback'], array_merge(array($this), $element['callback-args']));
            } else {
              $callbackStatus = call_user_func($element['callback'], $this);
            }
          }
        } 
        // The form element has no value set
        else {

          // Set element to null, then we know it was not set.
          //$element['value'] = null;

          // If the element is a checkbox, clear its value of checked.
          if($element['type'] === 'checkbox') {
            $element['checked'] = false;
          }

          // Do validation even when the form element is not set? Duplicate code, revise this section and move outside this if-statement?
          if(isset($element['validation'])) {
            $element['validation-pass'] = $element->Validate($element['validation'], $this);
            if($element['validation-pass'] === false) {
              $values[$element['name']] = array('value'=>$element['value'], 'validation-messages'=>$element['validation-messages']);
              $validates = false;
            }
          }


        }
      }
    } elseif(isset($_SESSION['form-failed'])) {
      foreach($_SESSION['form-failed'] as $key => $val) {
        $this[$key]['value'] = $val['value'];
        if(isset($val['validation-messages'])) {
          $this[$key]['validation-messages'] = $val['validation-messages'];
          $this[$key]['validation-pass'] = false;
        }
      }
      unset($_SESSION['form-failed']);
    } elseif(isset($_SESSION['form-remember'])) {
      foreach($_SESSION['form-remember'] as $key => $val) {
        $this[$key]['value'] = $val['value'];
      }
      unset($_SESSION['form-remember']);
    }
        
    if($validates === false || $callbackStatus === false) {
      $_SESSION['form-failed'] = $values;
    } elseif($remember) {
      $_SESSION['form-remember'] = $values;
    }
    
    return ($validates) ? $callbackStatus : $validates;
  }
  
  
}

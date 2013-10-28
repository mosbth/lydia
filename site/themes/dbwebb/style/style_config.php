<?php
/**
 * Enable configuration details for style.php.
 *
 * Make configurations here to make it easer to use one installed base of lessphp on a server 
 * and to make it easier to make non breaking updates to style.php.
 *
 */
//
// Path to lessphp compiler include script 
//
//$config['path'], dirname(__FILE__)."/lessphp/lessc.inc.php";
$config['path'] = dirname(__FILE__)."/../../../../themes/base/style/lessphp/lessc.inc.php";

//
// Include import paths for compiler
//
//$config['imports'] = null;
$config['imports'] = array(dirname(__FILE__)."/../../../../themes/base/style/");


//
// Output format for resulting css-code, set to null for default.
//
// lessjs (default) — Same style used in LESS for JavaScript
// compressed — Compresses all the unrequired whitespace
// classic — lessphp’s original formatter
//
$config['formatter'] = null;
//$config['formatter'] = 'compressed';


//
// Preserve /* */ comments in output, true to preserve comments.
//
$config['comments'] = false;


//
// Extend less language by register own functions to lessphp compiler.
//
$config['functions'] = array(

  //
  // Function unit
  //
  // mixins.less:  font: 100.01%/(unit((@magicNumber)/unit(@fontSizeBody))) @fontFamilyBody;
  // mixins.less:  line-height: unit(@magicNumber/(@fontSize*@fontSizeBody)); 
  //
  'unit' => function($arg) {
    list($type, $value) = $arg;
    return array($type, $value);
  },

);

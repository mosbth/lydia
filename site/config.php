<?php
/**
 * Site configuration, this file is change by user per site.
 *
 */

/*
 * Set level of error reporting
 */
error_reporting(-1); 

/*
 * Define session name
 */
$ly->cfg['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);

/*
 * Define server timezone
 */
$ly->cfg['timezone'] = 'Europe/Stockholm';

/*
 * Define internal character encoding to UTF-8
 */
$ly->cfg['character_encoding'] = 'UTF-8';


/**
 * Define the controllers, their classname and enable/disable them.
 *
 * The array-key is matched against the url, for example: 
 * the url 'user/login' would instantiate the controller with the key "user", that is CCtrl4User
 * and call the method "login" in that class. This process is managed in:
 * $ly->FrontControllerRoute();
 * and this method is called in the frontcontroller phase from index.php.
 */
$ly->cfg['controllers'] = array(
  'index'         => array('enabled' => true,'class' => 'CCtrl4Index'),
  'mumintrollet'  => array('enabled' => true,'class' => 'CCtrl4Mumintrollet'),
);


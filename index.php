<?php
/**
 * All requests routed through here. This is an overview of what actaully happens during
 * a request.
 *
 * @package LydiaCore
 */

// ---------------------------------------------------------------------------------------
//
// PHASE: BOOTSTRAP
//
define('LYDIA_INSTALL_PATH', __DIR__);
define('LYDIA_SITE_PATH',   LYDIA_INSTALL_PATH . '/site');
define('LYDIA_CONFIG_PATH', LYDIA_SITE_PATH . '/config.php');
define('LYDIA_DATA_PATH',   LYDIA_SITE_PATH . '/data');

require(LYDIA_INSTALL_PATH.'/src/bootstrap.php');

$ly = CLydia::Instance()->Init();


// Allow siteowner to add own code or overwrite existning. Call init function if defined.
require(LYDIA_SITE_PATH.'/functions.php');
if(function_exists('lySiteInit')) {
  lySiteInit();
}


// ---------------------------------------------------------------------------------------
//
// PHASE: FRONTCONTROLLER ROUTE
//
if(!defined('LYDIA_PASS_FRONTCONTROLLER')) {
  $ly->FrontControllerRoute();
}


// ---------------------------------------------------------------------------------------
//
// PHASE: THEME ENGINE RENDER
//
if(!defined('LYDIA_PASS_THEMEENGINE')) {
  $ly->ThemeEngineRender();
}

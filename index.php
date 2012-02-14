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
define('LYDIA_INSTALL_PATH', dirname(__FILE__));
define('LYDIA_SITE_PATH', LYDIA_INSTALL_PATH . '/site');

require(LYDIA_INSTALL_PATH.'/src/bootstrap.php');

$ly = CLydia::Instance();


// ---------------------------------------------------------------------------------------
//
// PHASE: FRONTCONTROLLER ROUTE
//
$ly->FrontControllerRoute();


// ---------------------------------------------------------------------------------------
//
// PHASE: THEME ENGINE RENDER
//
$ly->ThemeEngineRender();

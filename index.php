<?php
// PHASE: BOOTSTRAP
define('LYDIA_INSTALL_PATH', dirname(__FILE__));
require(LYDIA_INSTALL_PATH.'/src/bootstrap.php');
$ly = CLydia::GetInstance();

// PHASE: FRONTCONTROLLER ROUTE
$ly->FrontControllerRoute();


// PHASE: TEMPLATE ENGINGE RENDER
$ly->TemplateEngineRender();

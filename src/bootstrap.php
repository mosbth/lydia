<?php
/**
 * Bootstrapping, setting up and loading the core.
 *
 * @package LydiaCore
 */

/**
 * Enable auto-load of class declarations.
 */
function __autoload($aClassName) {
	$file1 = LYDIA_INSTALL_PATH . "/src/{$aClassName}.php";
	$file2 = LYDIA_INSTALL_PATH . "/site/src/{$aClassName}.php";
	if(is_file($file1)) {
		require_once($file1);
	} elseif(is_file($file2)) {
		require_once($file2);
	}
}

/**
 * Interface for classes implementing the singleton pattern.
 */
interface ISingleton {
	public static function GetInstance();
}

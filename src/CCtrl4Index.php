<?php
/**
 * Standard controller to implement the first standard home page.
 * 
 * @package LydiaCore
 */
class CCtrl4Index implements IController {

	/**
 	 * Implementing interface IController. All controllers must have an index action.
	 */
	public function Index() {	
		global $ly;

    echo "<hr><h2>Welcome to the mighty CCtrl4Index::Index()</h2>";

	}


} // End of class
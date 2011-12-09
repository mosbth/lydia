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
    $ly->template->main = <<<EOD
<hr><h2>Welcome to the mighty CCtrl4Index::Index()</h2>
<p>This is a sample controller.</p>
EOD;
  }


	/**
 	 * A debug funktion to displays all values of $ly.
	 */
  public function Debug() {	
    global $ly;
    $ly->template->main = "<h1>Debuginformation</h1><p>The content of \$ly is:</p><pre>".print_r($ly, true)."</pre>";
  }


} // End of class
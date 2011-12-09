<?php
/**
 * Testcontroller.
 * 
 * @package LydiaCore
 */
class CCtrl4Mumintrollet implements IController {

	/**
 	 * Implementing interface IController. All controllers must have an index action.
	 */
	public function Index() {	
		global $ly;

    echo "<hr><h2>The controller of Mumintrollet says hi!</h2>";

	}


	/**
 	 * A test method for baking cookies.
	 */
	public function Baka($val1=null, $val2=null, $val3=null, $val4=null) {	
		global $ly;

    echo "<hr><h2>Baka, baka , baka</h2>";
    echo "<p>The ingridience are:<p>";
    var_dump($val1);
    var_dump($val2);
    var_dump($val3);
    var_dump($val4);
    echo "<p>And the result is FIKA :D</p>";
	}


} // End of class
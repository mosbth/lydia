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
    $ly->template->main = "<hr><h2>The controller of Mumintrollet says hi!</h2>";
	}


	/**
 	 * A test method for baking cookies.
	 */
	public function Baka($val1=null, $val2=null, $val3=null, $val4=null) {	
		global $ly;
    $arg1 = $val1 ? htmlentities($val1) : 'null';
    $arg2 = $val2 ? htmlentities($val2) : 'null';
    $arg3 = $val3 ? htmlentities($val3) : 'null';
    $arg4 = $val4 ? htmlentities($val4) : 'null';
    
    $ly->template->main = <<<EOD
<hr><h2>Baka, baka , baka</h2>
<p>The ingridience are:<p>
<p>\$arg1 = $arg1<br/>
\$arg2 = $arg2<br/>
\$arg3 = $arg3<br/>
\$arg4 = $arg4</p>
<p>And the result is FIKA :D</p>
EOD;
  }


} // End of class
<?php
/**
 * Controller for development and testing purpose, helpful methods for the developer.
 * 
 * @package LydiaCore
 */
class CCDeveloper implements IController {

	/**
 	 * Implementing interface IController. All controllers must have an index action.
	 */
	public function Index() {	
		$this->Menu();
	}


	/**
 	 * Create a list of links in the supported ways.
	 */
	public function Links() {	
		$this->Menu();
		
		$ly = CLydia::Instance();
		
		$url = 'developer/links';
		$current      = $ly->request->CreateUrl($url);

		$ly->request->cleanUrl = false;
		$ly->request->querystringUrl = false;		
		$default      = $ly->request->CreateUrl($url);
		
		$ly->request->cleanUrl = true;
		$clean        = $ly->request->CreateUrl($url);		
		
		$ly->request->cleanUrl = false;
		$ly->request->querystringUrl = true;		
		$querystring  = $ly->request->CreateUrl($url);
		
		$ly->data['main'] .= <<<EOD
<h2>CRequest::CreateUrl()</h2>
<p>Here is a list of urls created using above method with various settings. All links should lead to
this same page.</p>
<ul>
<li><a href='$current'>This is the current setting</a>
<li><a href='$default'>This would be the default url</a>
<li><a href='$clean'>This should be a clean url</a>
<li><a href='$querystring'>This should be a querystring like url</a>
</ul>
<p>Enables various and flexible url-strategy.</p>
EOD;
	}


	/**
 	 * Create a method that shows the menu, same for all methods
	 */
	private function Menu() {	
		$ly = CLydia::Instance();
		$menu = array('developer', 'developer/index', 'developer/links');
		
		$html = null;
		foreach($menu as $val) {
		  $html .= "<li><a href='" . $ly->request->CreateUrl($val) . "'>$val</a>";  
		}
		
		$ly->data['title'] = "The Developer Controller";
		$ly->data['main'] = <<<EOD
<h1>The Developer Controller</h1>
<p>This is what you can do for now:</p>
<ul>
$html
</ul>
EOD;
  }
  
} 
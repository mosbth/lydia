<?php
/**
 * A container to hold a bunch of views.
 *
 * @package LydiaCore
 */
class CViewContainer {

	/**
	 * Members
	 */
	private $data = array();
	private $views = array();
	

	/**
	 * Constructor
	 */
	public function __construct() { ; }


	/**
	 * Getters.
	 */
  public function GetData() { return $this->data; }
  
  
	/**
	 * Set the title of the page.
	 *
	 * @param $value string to be set as title.
	 */
	public function SetTitle($value) {
    $append = CLydia::Instance()->config['title_append'];
    $separator = CLydia::Instance()->config['title_separator'];

    if($append) {
      $value .= "{$separator}{$append}";
    }
    return $this->SetVariable('title', $value);
  }


  /**
   * Set any variable that should be available for the theme engine.
   *
   * @param $value string to be set as title.
   * @return $this.
   */
  public function SetVariable($key, $value) {
    $this->data[$key] = $value;
    return $this;
  }

  
  /**
   * Get variable that should be available for the theme engine.
   *
   * @param $value string to be set as title.
   * @return mixed as variable value or null if it does not exists.
   */
  public function GetVariable($key) {
    return isset($this->data[$key]) ? $this->data[$key] : null ;
  }

  
  /**
   * Add inline style.
   *
   * @param $value string to be added as inline style.
   * @return $this.
   */
  public function AddStyle($value) {
    if(isset($this->data['inline_style'])) {
      $this->data['inline_style'] .= $value;
    } else {
      $this->data['inline_style'] = $value;
    }
    return $this;
  }

  
  /**
   * Add a view as file to be included and optional variables. 
   * 
   * @deprecated v0.3.01
   * @param $file string path to the file to be included.
   * @param $vars array containing the variables that should be avilable for the included file.
   * @param $region string the theme region, uses string 'default' as default region.
   * @return $this.
   */
  public function AddInclude($file, $variables=array(), $region='default') {
    $this->views[$region][] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
    return $this;
  }
  

   /**
   * Add text and optional variables.
   *
   * @deprecated v0.3.01
   * @param $string string content to be displayed.
   * @param $vars array containing the variables that should be avilable for the included file.
   * @param $region string the theme region, uses string 'default' as default region.
   * @return $this.
   */
  public function AddString($string, $variables=array(), $region='default') {
    $this->views[$region][] = array('type' => 'string', 'string' => $string, 'variables' => $variables);
    return $this;
  }


 /**
   * Add a view as file to be included in a region with optional variables.
   *
   * @param mixed $region the theme region as a string or array which contains all arguments..
   * @param string $file path to the file to be included. 
   * @param array $vars containing the variables that should be avilable for the included file.
   * @param integer $order value to sort views by.
   * @return $this.
   */
  public function AddIncludeToRegion($region, $file=null, $variables=array(), $order=0) {
    if(is_array($region)) {
      $order      = isset($region['order']) ? $region['order']  : $order;
      $variables  = isset($region['data'])  ? $region['data']   : $variables; 
      $file       = $region['content']; 
      $region     = $region['region'];
    }
    if(empty($file)) throw new Exception(t('View filename is empty.'));
    $this->views[$region][] = array('type' => 'include', 'file' => $file, 'variables' => $variables, 'order' => $order);
    return $this;
  }
  

  /**
   * Add string to region.
   *
   * @param string $region the theme region.
   * @param string $string content to be displayed.
   * @param array $vars containing the variables that should be available for the string.
   * @param integer $order value to sort views by.
   * @return $this.
   */
  public function AddStringToRegion($region, $string=null, $variables=array(), $order=0) {
    if(is_array($region)) {
      $order      = isset($region['order']) ? $region['order']  : $order;
      $variables  = isset($region['data']) ? $region['data']  : $variables; 
      $string     = $region['content']; 
      $region     = $region['region']; 
    }
    $this->views[$region][] = array('type' => 'string', 'string' => $string, 'variables' => $variables, 'order' => $order);
    return $this;
  }


  /**
   * Add function to region.
   *
   * @param string $region the theme region.
   * @param string $string content to be displayed.
   * @param array $vars containing the variables that should be available for the string.
   * @param integer $order value to sort views by.
   * @return $this.
   */
  public function AddFunctionToRegion($region, $function=null, $variables=array(), $order=0) {
    if(is_array($region)) {
      $order      = isset($region['order']) ? $region['order']  : $order;
      $variables  = isset($region['data']) ? $region['data']  : $variables; 
      $function   = $region['content']; 
      $region     = $region['region']; 
    }
    $this->views[$region][] = array('type' => 'function', 'function' => $function, 'variables' => $variables, 'order' => $order);
    return $this;
  }


  /**
   * Add class to region.
   *
   * @param string $region the theme region.
   * @param string $class the class to add.
   * @return $this.
   */
  public function AddClassToRegion($region, $class) {
    $this->views[$region]['class'] = $class;
    return $this;
  }


  /**
   * Check if there is a class attribute attached to the view.
   *
   * @param string $region the theme region(s).
   * @return mixed boolean true if region has a class else null.
   */
  public function RegionHasClass($region) {
    return isset($this->views[$region]['class']) ? $this->views[$region]['class'] : null;
  }
  
  
  /**
   * Add mixed data from array.
   *
   * @param array $arg containing array with data to add.
   * @return $this.
   */
  public function Add($args=array()) {
    // Assume the args is just one regions
    if(!isset($args['regions'])) {
      $args['regions'] = $args;
    }

    if(isset($args['title'])) {
      $this->SetTitle($args['title']);
    }

    foreach($args['regions'] as $val) {
      switch($val['type']) {
        case 'string':    $this->AddStringToRegion($val);   break;
        case 'include':   $this->AddIncludeToRegion($val);  break;
        case 'function':  $this->AddFunctionToRegion($val); break;
        default: throw new Exception(t("No such type for adding region to view container: '@type'.", array('@type'=>$val['type'])));
      }
    }

    return $this;
  }


  /**
   * Check if there exists views for a specific region.
   *
   * @param $region string/array the theme region(s).
   * @return boolean true if region has views, else false.
   */
  public function RegionHasView($region) {
    if(is_array($region)) {
      foreach($region as $val) {
        if(isset($this->views[$val])) {
          return true;
        }
      }
      return false;
    } else {
      return(isset($this->views[$region]));
    }
  }
  
  
  /**
   * Render all views according to their type.
   * 
   * @param $region string the region to render views for.
   */
  public function Render($region='default') {
    if(!isset($this->views[$region])) return;

    usort($this->views[$region], function($a, $b) {
      if($a['order'] == $b['order']) {
        return 0;
      }
      return ($a['order'] < $b['order']) ? -1 : 1;
    });

    foreach($this->views[$region] as $view) {
      switch($view['type']) {

        case 'include':   
          if(isset($view['variables'])) {
            extract($view['variables']);             
          }
          include($view['file']); 
        break;

        case 'string':
          if(isset($view['variables'])) {
            extract($view['variables']);
          }
         echo $view['string']; 
        break;

        case 'function':  
          $data = isset($view['variables']) ? $view['variables'] : array(); 
          echo $view['function']($data); 
        break;
      }
    }
  }
  

}
<?php
/**
 * A test controller for themes.
 * 
 * @package LydiaCore
 */
class CCTheme extends CObject implements IController {


  /**
   * Constructor
   */
  public function __construct() { parent::__construct(); 
    $this->views->AddStyle('body:hover{background:#fff url('.$this->request->base_url.'themes/base/img/grid_24_30_10.png) repeat-y center top;}');
  }


  /**
   * Display what can be done with this controller.
   */
  public function Index() {
    // Get a list of all kontroller methods
    $rc = new ReflectionClass(__CLASS__);
    $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
    $items = array();
    foreach($methods as $method) {
      if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
        $items[] = $this->request->controller . '/' . mb_strtolower($method->name);
      }
    }

    $data = array(
      'theme_name' => $this->config['theme']['name'],
      'methods' => $items,
    );

    $this->views->SetTitle('Theme')
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'), $data);
  }

/*
    $this->views->SetTitle(t('Home'))
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'))
                ->AddIncludeToRegion('sidebar', $this->LoadView('sidebar.tpl.php')
    );
*/

  /**
   * Put content in some regions.
   */
  public function SomeRegions() {
    $this->views->SetTitle('Theme display content for some regions')
                ->AddString('This is the primary region', array(), 'primary');
                
    if(func_num_args()) {
      foreach(func_get_args() as $val) {
        $this->views->AddString("This is region: $val", array(), $val)
                    ->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
      }
    }
  }


  /**
   * Put content in all regions.
   */
  public function AllRegions() {
    $this->views->SetTitle('Theme display content for all regions');
    foreach($this->config['theme']['regions'] as $val) {
      $this->views->AddString("This is region: $val", array(), $val)
                  ->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
    }
  }


  /**
   * Display text as h1h6 and paragraphs with some inline formatting. OBSOLETE
   */
  public function H1H6() {
    $this->views->SetTitle('Theme testing headers and paragraphs')
                ->AddIncludeToRegion('primary', $this->LoadView('h1h6.tpl.php'));
  }


  /**
   * Display text to view typographic settings
   */
  public function Typography() {
    $this->views->SetTitle('Theme typography testing')
                ->AddIncludeToRegion('primary', $this->LoadView('typography.tpl.php'))
                ->AddIncludeToRegion('sidebar', $this->LoadView('typography_sidebar.tpl.php'));
  }


} 
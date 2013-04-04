<?php
/**
 * Load content associated with url as a single page.
 * 
 * @package LydiaCore
 */
class CPageLoader extends CObject {

  /**
   * Members
   */
  protected $options;
  protected $page;


  /**
   * Constructor
   *
   * @param array $options to change the default options used for this class.
   */
  public function __construct($options = array()) { 
    parent::__construct(); 

    $defaults = array(
      'index_template'    => 'index',
      'sidebar_template'  => 'sidebar',
      'suffix_template'   => '.tpl.php',
      'breadcrumb'        => true,
    );
    $this->options = array_merge($defaults, $options);
  }
  


  /**
   * Create a instance of this class, custom instance if specified.
   *
   * @param string $class a valid classname.
   * @return CPageLoader an instance of the created class.
   */
  public static function Factory($class=null) {
    if(isset($class)) {
      return new $class();
    }
    return new self();
  }



  /**
   * Check if the url is associated with some content.
   *
   * @param string $url the url to check.
   * @return boolean true if url is associated with content, otherwise false.
   */
  public static function UrlHasContent($url) {
    $c = new CMContent();
    return $c->ContentHasUrl($url);
  }



  /**
   * Load and display content by its associated url.
   *
   * @param string $url the url to check.
   */
  public function DisplayContentByUrl($url) {
    $this->content = new CMContent();
    $c = &$this->content;
    $url = empty($url) ? 'home' : $url;
    if(!$c->LoadByUrl($url)) {
      $this->ShowErrorPage(404, t('Page is not found. No such content.'));
    }
    $o = &$this->options;

    $data = array(
      'user_is_admin' => $this->user->IsAdmin(),
      'content' => $c->Prepare(),
    );

    $c->LoadParents();
    $this->page['title'] = $c->AddParentsTitle($c['title']);

    // Call template method if exists.
    $template = isset($c['template']) ? $c['template'] : null;;
    $method = 'Template_' . $template;
    if($template && method_exists($this, $method)) {
      $this->$method();
    }

    if($o['breadcrumb']) {
      $this->page['regions'][]  = array(
        'region'  => 'breadcrumb', 
        'type'    => 'string', 
        'content' => $this->CreateBreadcrumb($c->CreateBreadcrumbFromParents()),
      );
    }

    if($o['index_template']) {
      $templateName = isset($o['index_template_name']) ? $o['index_template_name'] : $template;
      $templateName = empty($templateName) ? null : $templateName . '_';
      $this->page['regions'][]  = array(
        'region'  => 'primary', 
        'type'    => 'include', 
        'content' => $this->LoadView($templateName . $o['index_template'] . $o['suffix_template']),
        'data'    => $data, 
      );
    }

    if($o['sidebar_template']) {
      $templateName = isset($o['sidebar_template_name']) ? $o['sidebar_template_name']  : $template;
      $templateName = empty($templateName) ? null : $templateName . '_';
      $this->page['regions'][]  = array(
        'region'  => 'sidebar', 
        'type'    => 'include', 
        'content' => $this->LoadView($templateName . $o['sidebar_template'] . $o['suffix_template']),
        'data'    => $data, 
      );
    }

    $this->views->Add($this->page);
  }



} 
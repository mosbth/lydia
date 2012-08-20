<?php
/**
 * A blog controller to display a blog-like list of content considered blog posts.
 * 
 * @package LydiaCore
 */
class CCBlog extends CObject implements IController {

  /**
   * Properties
   */
  private $options;
  
  
  /**
   * Constructor
   *
   * @param array $options to configure its look and feel.
   */
  public function __construct($options=array()) {
    parent::__construct();
    $default = array(
      'content_type' => 'post',
      'content_order_by' => 'created',  
      'content_order_order' => 'DESC',
      'content_limit' => 15,
      'breadcrumb_first' => t('Blog'),
      'title_index' => t('Index'),
      'title_app' => t('blog'),
      'title_separator' => ' - ',
      'post_format_short' => false,
    );
    $this->options = array_merge($default, $options);
  }


  /**
   * Display all content of choosen type.
   */
  public function Index() {
    $o = $this->options;
    $content = new CMContent();

    $title      = $o['title_index'] . $o['title_separator'] . $o['title_app'];
    $breadcrumb = $this->CreateBreadcrumb(array(
      array('label' => $o['breadcrumb_first'], 'url' => $this->CreateUrlToController()),
    ));
    $contents = $content->GetEntries(array('type'=>$o['content_type'], 'order_by'=>$o['content_order_by'], 'order_order'=>$o['content_order_order'], 'limit'=>$o['content_limit']));
    
    $this->views->SetTitle($title)
                ->AddStringToRegion('breadcrumb', $breadcrumb)
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'), array(
                  'contents' => $contents,
                  'user_is_admin_or_owner' => $this->user->IsAdmin(),
                  'post_format_short' => $o['post_format_short'],
                  'order_by_updated' => $o['content_order_by'] === 'updated',
                ))
                ->AddIncludeToRegion('sidebar', $this->LoadView('index_sidebar.tpl.php'), array(
                  'contents' => $contents,
                ));
  }


  /**
   * Display a particular blogpost based on its key.
   *
   * @param string key the key of the content to display. 
   */
  public function CatchAll($key=null) {
    $o = $this->options;
    $content = new CMContent();
    
    if($content->LoadByKey($key) && $content['type'] == $o['content_type']) {
      $content->Prepare();
      $contents = $content->GetEntries(array('type'=>$o['content_type'], 'order_by'=>$o['content_order_by'], 'order_order'=>$o['content_order_order']));
      $title      = htmlEnt($content['title']) . $o['title_separator'] . $o['title_app'];
      $breadcrumb = $this->CreateBreadcrumb(array(
        array('label' => $o['breadcrumb_first'], 'url' => $this->CreateUrlToController()),
        array('label' => htmlEnt($content['title']), 'url' => $this->CreateUrlToController($key)),
      ));
      
      $this->views->SetTitle($title)
                  ->AddStringToRegion('breadcrumb', $breadcrumb)
                  ->AddIncludeToRegion('primary', $this->LoadView('post.tpl.php'), array(
                    'content' => $content,
                    'user_is_admin_or_owner' => $this->user->IsAdmin(),
                    'order_by_updated' => $o['content_order_by'] === 'updated',
                  ))
                  ->AddIncludeToRegion('sidebar', $this->LoadView('post_sidebar.tpl.php'), array(
                    'contents' => $contents,
                  ));
    } else {
      $this->ShowErrorPage(404, t('No such entry.'));
    }
  }



} 
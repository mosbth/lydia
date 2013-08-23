
<?php
/**
 * A controller to display information about authors.
 * 
 * @package LydiaCore
 */
class CCAuthor extends CObject implements IController {

  /**
   * Properties
   */
  private $options;
  private $content;
  private $title;
  private $breadcrumb;
  private $data;
  private $primary;
  private $sidebar;
  private $iconSet;
  private $pageKey;
  
  
  /**
   * Constructor
   *
   * @param array $options to configure its look and feel.
   */
  public function __construct($options=array()) {
    parent::__construct();

    $default = array(
      'content_type'          => null,
      'content_order_by'      => 'updated desc, created desc',  
      'content_order_order'   => null, //'DESC',
      'content_limit'         => 7,
      'search_limit'          => 10,

      'breadcrumb_first'      => t('Author'),
      'title_index'           => null, //t('Index'),
      'title_app'             => t('author'),
      'title_separator'       => $this->config['title_separator'],
      'post_format_short'     => true,

      // Display descriptive text 
      'intro_title'            => t('Author'),
      'intro_content'          => t('Here is an overview of the authors.'),

      // What content should be displayed in the sidebar?
      'sidebar_default'       => array('intro', 'toc', 'current', 'categories'),      
      'sidebar_main'          => array('current'),
      'sidebar_author'        => array('intro', 'toc'),
    );

    $this->options = array_merge_recursive_distinct($default, $options);
  }



  /**
   * Commons when displaying entries.
   *
   * @param array $args add och change whats loaded for content.
   */
  protected function Init($args=array()) {
    $o = $this->options;
    $c = $this->content = new CMContent();

    // Get details for pagination (could gather this in function or class later on?)
    $limit = $o['content_limit'];
    $pageCurrent = isset($_GET['p']) && is_numeric($_GET['p']) ? $_GET['p'] : 1;
    $offset = ($pageCurrent > 0 ? $pageCurrent - 1 : 0) * $limit;
   
    $default = array(
      'type'        => $o['content_type'], 
      'order_by'    => $o['content_order_by'],
      'order_order' => $o['content_order_order'],
      'limit'       => $limit,
      'offset'      => $offset,
    );
    $args = array_merge($default, $args);

    $c->GetEntries($args);
    $items = $c->Count();
    $total = $c->hits;

    $this->data = array(
      'contents'           => $c,
      'user_is_admin'      => $this->user->IsAdmin(),
      'post_format_short'  => $o['post_format_short'],
      'order_by_updated'   => $o['content_order_by'] === 'updated',
      'categories'         => $c->GetCategories(array('type'=>$o['content_type'])),
      'sidebar_contains'   => $o['sidebar_default'],
      'intro'              => array('title' => $o['intro_title'], 'content' =>  $o['intro_content']),
      'hits'               => $items,
      'first_hit'          => $offset + 1,
      'last_hit'           => $offset + $items,
      'total_hits'         => $total,
      'first_page'         => 1,
      'last_page'          => ceil($total / $limit),
      'current_page'       => $pageCurrent, 
      'pagination_url'     => $this->CreateUrlToControllerMethodArguments(),
    );

    $this->breadcrumb = array(
      array('label' => $o['breadcrumb_first'], 'url' => $this->CreateUrlToController()),
    );

    $this->primary = 'index.tpl.php';
    $this->sidebar = 'index_sidebar.tpl.php';
  }



  /**
   * Commons for output.
   */
  protected function Output() {
    $o = $this->options;
    $this->title = isset($o['title_index']) ?  $o['title_index'] . $o['title_separator'] . $o['title_app'] : $o['title_app'];

    $this->views->SetTitle($this->title)
                ->AddStringToRegion('sidebar', $this->icons, null, -1)
                ->AddIncludeToRegion('primary', $this->LoadView($this->primary), $this->data);

    if($this->breadcrumb) {
      $this->views->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb));
    }

    if($this->sidebar) {
      $this->views->AddIncludeToRegion('sidebar', $this->LoadView($this->sidebar), $this->data);      
    }
  }



  /**
   * Display all content of choosen type.
   */
  public function Index() {
    //$this->Init();
    $o = $this->options;
    $c = $this->content = new CMContent();
die();
    // Get details for pagination (could gather this in function or class later on?)
    $limit = $o['content_limit'];
    $pageCurrent = isset($_GET['p']) && is_numeric($_GET['p']) ? $_GET['p'] : 1;
    $offset = ($pageCurrent > 0 ? $pageCurrent - 1 : 0) * $limit;
   
    $default = array(
      'type'        => $o['content_type'], 
      'order_by'    => $o['content_order_by'],
      'order_order' => $o['content_order_order'],
      'limit'       => $limit,
      'offset'      => $offset,
    );
    $args = array_merge($default, $args);

    $c->GetEntries($args);
    $items = $c->Count();
    $total = $c->hits;

    $this->data = array(
      'contents'           => $c,
      'user_is_admin'      => $this->user->IsAdmin(),
      'post_format_short'  => $o['post_format_short'],
      'order_by_updated'   => $o['content_order_by'] === 'updated',
      'categories'         => $c->GetCategories(array('type'=>$o['content_type'])),
      'sidebar_contains'   => $o['sidebar_default'],
      'intro'              => array('title' => $o['intro_title'], 'content' =>  $o['intro_content']),
      'hits'               => $items,
      'first_hit'          => $offset + 1,
      'last_hit'           => $offset + $items,
      'total_hits'         => $total,
      'first_page'         => 1,
      'last_page'          => ceil($total / $limit),
      'current_page'       => $pageCurrent, 
      'pagination_url'     => $this->CreateUrlToControllerMethodArguments(),
    );

    $this->breadcrumb = array(
      array('label' => $o['breadcrumb_first'], 'url' => $this->CreateUrlToController()),
    );

    $this->primary = 'index.tpl.php';
    $this->sidebar = 'index_sidebar.tpl.php';
    $this->data['sidebar_contains'] = $this->options['sidebar_main'];

    //$this->Output();
    //$o = $this->options;
    $this->title = isset($o['title_index']) ?  $o['title_index'] . $o['title_separator'] . $o['title_app'] : $o['title_app'];

    $this->views->SetTitle($this->title)
                ->AddStringToRegion('sidebar', $this->icons, null, -1)
                ->AddIncludeToRegion('primary', $this->LoadView($this->primary), $this->data);

    if($this->breadcrumb) {
      $this->views->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb));
    }

    if($this->sidebar) {
      $this->views->AddIncludeToRegion('sidebar', $this->LoadView($this->sidebar), $this->data);      
    }
  }



  /**
   * Display a particular blogpost based on its key.
   *
   * @param string key the key of the content to display. 
   */
  public function CatchAll($key=null) {
/*
    $o = $this->options;
    $content = new CMContent();
    
    if($content->LoadByKey($key) && $content['type'] == $o['content_type']) {
      $this->Init();
      $this->pageKey = $key;

      $this->data['content'] = $content->Prepare();
      $this->data['sidebar_contains'] = $this->options['sidebar_author'];

      $title = htmlEnt($content['title']);
      $this->options['title_index'] = $title;
      $this->breadcrumb[] = array('label' => $title, 'url' => $this->CreateUrlToController($key));

      $this->primary = 'author.tpl.php';

      $this->Output();
    } else {
      $this->ShowErrorPage(404, t('No such entry.'));
    }
  }

*/

    $o = $this->options;
    $c = $this->content = new CMContent();

    // Check if there is a user
    $author = $this->user->GetUserByAcronym($key);
    if(!$author) {
      $this->ShowErrorPage(404, t('No such author.'));
    }

    // Get details for pagination (could gather this in function or class later on?)
    $limit = $o['content_limit'];
    $pageCurrent = isset($_GET['p']) && is_numeric($_GET['p']) ? $_GET['p'] : 1;
    $offset = ($pageCurrent > 0 ? $pageCurrent - 1 : 0) * $limit;
   
    $default = array(
      'author'      => $author['id'], 
      'type'        => $o['content_type'], 
      'order_by'    => $o['content_order_by'],
      'order_order' => $o['content_order_order'],
      'limit'       => $limit,
      'offset'      => $offset,
    );
    $args = $default; //array_merge($default, $args);

    $c->GetEntries($args);
    $items = $c->Count();
    $total = $c->hits;

    $this->data = array(
      'author'             => $author,
      'contents'           => $c,
      'user_is_admin'      => $this->user->IsAdmin(),
      'post_format_first_pragraph'  => true,
      'order_by_updated'   => $o['content_order_by'] === 'updated',
      'categories'         => $c->GetCategories(array('type'=>$o['content_type'])),
      'sidebar_contains'   => $o['sidebar_default'],
      'intro'              => array('title' => $o['intro_title'], 'content' =>  $o['intro_content']),
      'hits'               => $items,
      'first_hit'          => $offset + 1,
      'last_hit'           => $offset + $items,
      'total_hits'         => $total,
      'first_page'         => 1,
      'last_page'          => ceil($total / $limit),
      'current_page'       => $pageCurrent, 
      'pagination_url'     => $this->CreateUrlToControllerMethodArguments(),
    );

    $this->breadcrumb = array(
      array('label' => $o['breadcrumb_first'], 'url' => $this->CreateUrlToController()),
      array('label' => $author['name'], 'url' => $this->CreateUrlToControllerMethod()),
    );

    $this->primary = 'index.tpl.php';
    $this->sidebar = 'index_sidebar.tpl.php';
    $this->data['sidebar_contains'] = $this->options['sidebar_main'];

    //$this->Output();
    //$o = $this->options;
    $o['title_index'] = $author['name'];
    $this->title = isset($o['title_index']) ?  $o['title_index'] . $o['title_separator'] . $o['title_app'] : $o['title_app'];

    $this->views->SetTitle($this->title)
                ->AddStringToRegion('sidebar', $this->icons, null, -1)
                ->AddIncludeToRegion('primary', $this->LoadView($this->primary), $this->data);

    if($this->breadcrumb) {
      $this->views->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb));
    }

    if($this->sidebar) {
      $this->views->AddIncludeToRegion('sidebar', $this->LoadView($this->sidebar), $this->data);      
    }
  }


} 


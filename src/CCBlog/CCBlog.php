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
  private $content;
  private $title;
  private $breadcrumb;
  private $data;
  private $primary;
  private $sidebar;
  
  
  /**
   * Constructor
   *
   * @param array $options to configure its look and feel.
   */
  public function __construct($options=array()) {
    parent::__construct();

    $default = array(
      'content_type'          => 'post',
      'content_order_by'      => 'created',  
      'content_order_order'   => 'DESC',
      'content_limit'         => 7,
      'search_limit'          => 10,

      // For Feed
      'feed_limit'            => 20,
      'feed_title'            => t('Feed for blog'),
      'feed_description'      => t('This is the feed of the latest posts to the blog.'),

      'breadcrumb_first'      => t('Blog'),
      'breadcrumb_category'   => t('Category:'),
      'title_index'           => null, //t('Index'),
      'title_app'             => t('blog'),
      'title_separator'       => $this->config['title_separator'],
      'post_format_short'     => true,

      // Display descriptive text on the blog 
      'intro_title'            => t('Blog'),      
      'intro_content'          => t('This is a blog with blogposts.'),

      // What content should be displayed in the sidebar?
      'sidebar_default'       => array('intro', 'toc', 'current', 'categories'),      
      'sidebar_main'          => array('current', 'categories'),
      'sidebar_search'        => array('categories'),
      'sidebar_categories'    => array('intro', 'current', 'categories'),
      'sidebar_post'          => array('intro', 'toc', 'categories'),

      // Icons to show
      'icons_default'         => array('search', 'rss'),
      'icons'                 => array(
        'search' => array(
          'href'  => $this->CreateUrlToController(m('search')),
          'title' => t('Search among the blogsposts'),
        ),
        'rss' => array(
          'href'  => $this->CreateUrlToController('rss'),
          'title' => t('RSS feed for the latest blogsposts'),
        ),
      ),
    );

    $this->options = array_merge_recursive_distinct($default, $options);
    $this->views->SetVariable('alternate_feed', $this->CreateUrlToController('rss'));
    $this->views->SetVariable('alternate_feed_title', $this->options['feed_title']);
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

    $this->icons = $this->Icons($o['icons_default'], $o['icons']);

    $this->views->SetTitle($this->title)
                ->AddStringToRegion('sidebar', $this->icons, null, -1)
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->breadcrumb))
                ->AddIncludeToRegion('primary', $this->LoadView($this->primary), $this->data)
                ->AddIncludeToRegion('sidebar', $this->LoadView($this->sidebar), $this->data);
  }



  /**
   * Display all content of choosen type.
   */
  public function Index() {
    $this->Init();
    $this->data['sidebar_contains'] = $this->options['sidebar_main'];
    $this->Output();
  }



  /**
   * Display all content from a category.
   *
   * @param string $category, the category key.
   */
  public function Category($category=null) {
    $this->Init(array('category_key'=>$category));

    $this->data['category'] = $this->content->GetCategory($category);
    if(!isset($this->data['category'])) $this->ShowErrorPage(404, t('The category does not exists.'));

    $this->data['sidebar_contains'] = $this->options['sidebar_categories'];
    $cat = $this->options['breadcrumb_category'].' '.$this->data['category']['title'];

    $this->options['title_index'] = $cat;
    $this->breadcrumb[] = array('label' => $cat, 'url' => $this->CreateUrlToController(null, $this->data['category']['key']));

    $this->Output();
  }
  

  
  /**
   * Display all content of choosen type.
   *
   * @param string $str the string to search for. 
   */
  public function Search($str=null) {
    $strDecode = urldecode($str);
    $form = new CForm(array(), array(
        'q' => array(
          'type'        => 'search',
          'class'       => 'span14',
          'value'       => $strDecode,
          'placeholder' => t('Search by keywords using "" AND OR'),
          'required'    => true,
          'validation'  => array('not_empty'),
          'label'       => t('Search'),
          'callback'  => function($f) {
            return true;
          }
        ),
      )
    );

    $status = $form->Check();
    if ($status === true) {
      $this->RedirectToCurrentControllerMethod(urlencode($form['q']['value']));
    }
 
    $o = $this->options;
    $c = $this->content = new CMContent();

    // Get details for pagination (could gather this in function or class later on?)
    $limit = $o['search_limit'];
    $pageCurrent = isset($_GET['p']) && is_numeric($_GET['p']) ? $_GET['p'] : 1;
    $offset = ($pageCurrent > 0 ? $pageCurrent - 1 : 0) * $limit;

    $args = array(
      'type'    => $o['content_type'], 
      'limit'   => $limit,
      'offset'  => $offset,
      'match'   => $strDecode,
    );
    
    $c->SearchEntries($args);
    $items = $c->Count();
    $total = $c->hits;

    $this->data = array(
      'contents'           => $c,
      'categories'         => $c->GetCategories(array('type'=>$o['content_type'])),
      'sidebar_contains'   => $o['sidebar_search'],
      'intro'              => array('title' => $o['intro_title'], 'content' =>  $o['intro_content']),
      'form'               => $form,
      'did_search'         => !empty($str),
      'hits'               => $items,
      'first_hit'          => $offset + 1,
      'last_hit'           => $offset + $items,
      'total_hits'         => $total,
      'first_page'         => 1,
      'last_page'          => ceil($total / $limit),
      'current_page'       => $pageCurrent, 
      'pagination_url'     => $this->CreateUrlToControllerMethod($str),
    );

    // Create the breadcrumb
    $this->breadcrumb[] = array('label' => $o['breadcrumb_first'], 'url' => $this->CreateUrlToController());
    $this->breadcrumb[] = array('label' => t('Search'), 'url' => $this->CreateUrlToControllerMethod());

    if(!empty($str)) {
      $this->breadcrumb[] = array('label' => $strDecode, 'url' => $this->CreateUrlToControllerMethod($str));
      $this->options['title_index'] = t('Search for "!str"', array('!str' => $strDecode));
    } else {
      $this->options['title_index'] = t('Search');
    }

    $this->primary = 'search.tpl.php';
    $this->sidebar = 'index_sidebar.tpl.php';
    $this->Output();
  }



  /**
   * Add icons
   *
   * @param array $which icons.
   * @param array $icons all the configuration for all of the icons.
   * @return string html for icons.
   */
  protected function Icons($which, $icons, $options=array()) {
    $default = array(
      'path' => '/img/glyphicons/png/',
      'icons' => array(
        'search'  => 'glyphicons_027_search.png',
        'rss'     => 'glyphicons_397_rss.png',
      ),
      //'wrapper_element' => 'div', 
    );
    //$options = array_merge($default, $options);

    $html = "<div class='icons'><ul class='icons right'>\n";
    foreach ($which as $val) {
      $href   = $icons[$val]['href'];
      $src    = $default['path'] . $default['icons'][$val];
      $alt    = $val;
      $title  = $icons[$val]['title'];
      $html .= "\t<li><a href='{$href}'><img src='{$src}' alt='{$alt}' title='{$title}' width='24' height='24' /></a></li>\n";
    }

    return $html . "</ul></div>\n";
  }



  /**
   * Create a feed (RSS) for the entries.
   *
   */
  public function Rss() {
    $o = $this->options;
    $rss = new CMRSSFeedCreate(array(
      'title'       => $o['feed_title'],
      'description' => $o['feed_description'],
    ));
    $key = $this->request->controller . '-' . $this->request->method;

    if(!$rss->HasValidCache($key)) {
      $c = $this->content = new CMContent();

      $args = array(
        'type'        => $o['content_type'], 
        'order_by'    => $o['content_order_by'],
        'order_order' => $o['content_order_order'],
        'limit'       => $o['feed_limit'],
      );
          
      $c->GetEntries($args);
      $items = array();
      foreach($c as $content) {
        $item['title']        = preg_replace('/\s+/', ' ', htmlSpec(htmlDent($content['title'])));
        $item['description']  = preg_replace('/\s+/', ' ', htmlSpec(htmlDent($content->GetExcerpt(800) . '…')));
        $item['link']         = $this->CreateUrlToController($content['key']);
        $item['guid']         = $item['link'];
        $item['pubdate']      = date('r', strtotime($content->PublishTime()));
        $items[] = $item;
      }
      $rss->CreateFeed($key, $items);
    }

    $rss->ReadFeedAndExit($key);
  }



  /**
   * Display a particular blogpost based on its key.
   *
   * @param string key the key of the content to display. 
   */
  public function CatchAll($key=null) {

    // Manage localised method names
    $method = checkMethodL10n($key);
    if(method_exists($this, $method)) {
      return $this->$method((func_num_args() > 1 ? func_get_arg(1) : null));
    }

    // Manage catch all by loading content by key
 /*   if(isset($lang[$key])) {
      $arg = null;
      if(func_num_args() > 1) {
        $arg = func_get_arg(1);
      }
      return $this->$lang[$key]($arg);
    }
*/
    $o = $this->options;
    $content = new CMContent();
    
    if($content->LoadByKey($key) && $content['type'] == $o['content_type']) {
      $this->Init();

      $this->data['content'] = $content->Prepare();
      $this->data['sidebar_contains'] = $this->options['sidebar_post'];

      $title = htmlEnt($content['title']);
      $this->options['title_index'] = $title;
      $this->breadcrumb[] = array('label' => $title, 'url' => $this->CreateUrlToController($key));

      $this->primary = 'post.tpl.php';

      $this->Output();
    } else {
      $this->ShowErrorPage(404, t('No such entry.'));
    }
  }



} 
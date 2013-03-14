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

      // For RSS
      'rss_limit'             => 10,

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
      'sidebar_default'       => array('intro', 'toc', 'latest', 'categories'),      
      'sidebar_main'          => array('latest', 'categories'),
      'sidebar_search'        => array('latest', 'categories'),
      'sidebar_categories'    => array('intro', 'latest', 'categories'),
      'sidebar_post'          => array('intro', 'toc', 'latest', 'categories'),
    );

    $this->options = array_merge($default, $options);
  }



  /**
   * Commons when displaying entries.
   *
   * @param array $args add och change whats loaded for content.
   */
  protected function Init($args=array()) {
    $o = $this->options;
    $c = $this->content = new CMContent();

    $default = array(
      'type'        => $o['content_type'], 
      'order_by'    => $o['content_order_by'],
      'order_order' => $o['content_order_order'],
      'limit'       => $o['content_limit'],
      'match'       => false,
    );
    $args = array_merge($default, $args);
    
    $this->data = array(
      'contents'           => $c->GetEntries($args),
      'user_is_admin'      => $this->user->IsAdmin(),
      'post_format_short'  => $o['post_format_short'],
      'order_by_updated'   => $o['content_order_by'] === 'updated',
      'categories'         => $c->GetCategories(array('type'=>$o['content_type'])),
      'sidebar_contains'   => $o['sidebar_default'],
      'intro'              => array('title' => $o['intro_title'], 'content' =>  $o['intro_content']),
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
    $form = new CForm(array(), array(
        'q' => array(
          'type'        => 'search',
          'class'       => 'span14',
          'value'       => urldecode($str),
          'placeholder' => t('Search by keywords'),
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
    if($status === false) {
      //echo "false"; 
    }
    else if ($status === true) {
      $this->RedirectToCurrentControllerMethod(urlencode($form['q']['value']));
    }

    $this->Init(array('match'=>$str));
    $this->data['form'] = $form;
    $this->data['sidebar_contains'] = $this->options['sidebar_search'];
    $this->Output();
  }



  /**
   * Create a RSS for the entries.
   *
   */
  public function Rss() {
    $rss = new CMRSSFeedCreate();
    $key = $this->request->controller . '-' . $this->request->method;

    if(true or !$rss->HasValidCache($key)) {
      $o = $this->options;
      $c = $this->content = new CMContent();

      $args = array(
        'type'        => $o['content_type'], 
        'order_by'    => $o['content_order_by'],
        'order_order' => $o['content_order_order'],
        'limit'       => $o['rss_limit'],
      );
          
      $c->GetEntries($args);
      $items = array();
      foreach($c as $content) {
        $item['title']        = $content['title'];
        $item['description']  = $content->GetExcerpt(600);
        $item['link']         = $this->CreateUrlToController($content['key']);
        $item['guid']         = $item['link'];
        $item['pubdate']      = date('r', $content->PublishTime());
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
    $lang = array(
      'sok'       => 'Search',
      'kategori'  => 'Category',      
    );

    if(isset($lang[$key])) {
      $arg = null;
      if(func_num_args() > 1) {
        $arg = func_get_arg(1);
      }
      return $this->$lang[$key]($arg);
    }

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
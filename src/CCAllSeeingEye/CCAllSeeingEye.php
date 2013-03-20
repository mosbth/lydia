<?php
/**
 * Presentation of RSS aggregation, all combined, category and per feed.
 *
 * @package: LydiaApplication
 */
class CCAllSeeingEye extends CObject implements IController, IModule /*, ArrayAccess*/ {

  /**
   * Properties
   */
  private $options;
  
  
  /**
   * Constructor
   *
   * @param array $options to customize object.
   */
  public function __construct($options=array()) { 
    parent::__construct();
    $default = array(
      'aggregator_options' => array(
        //'config' => __DIR__ . '/config.php',
        'cache_duration' => 3600*4,
      ),

      // Text displayed at the index-page
      'intro' => array(
        'title' => t('RSS aggregation though the All-Seeing-Eye'),
        'description' => t('The module CCAllSeeingEye is a Lydia application which does RSS aggregation. Make an app-class who use this class, create a config.php-file to choose your feeds and customize the views to adapt it.'),
      ),

      // Page titles
      'title' => array(
        'index' => t('RSS aggregation'),
        'category' => t('@category (RSS aggregation)'),
        'feed' => t('@feed (RSS aggregation)'),
      ),

      // Label for the breadcrumb
      'breadcrumb' => array(
        array('label' => t('RSS aggregation'), 'url' => $this->CreateUrlToController()),
      ),
    );
    $this->options = array_replace_recursive($default, $options);
  }
  

  /**
   * Implementing ArrayAccess for $this->options
   */
/*  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->options[] = $value; } else { $this->options[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->options[$offset]); }
  public function offsetUnset($offset) { unset($this->options[$offset]); }
  public function offsetGet($offset) { return isset($this->options[$offset]) ? $this->options[$offset] : null; }
*/

  /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) { require_once(__DIR__.'/CCAllSeeingEyeModule.php'); $m = new CCAllSeeingEyeModule; return $m->Manage($action, $this->options['aggregator_options']); }

 
  /**
   * Index page.
   */
  public function Index() {
    $rssFeeds = new CMRSSAggregator();
    $rssFeeds->Load(array_merge($this->options['aggregator_options'], array('number_of_items'=>1)));

    $data = array(
      'feeds' => $rssFeeds->data,
      'intro' => $this->options['intro'],
      'categories' => $rssFeeds->GetCategories(),
    );

    $this->views->SetTitle($this->options['title']['index'])
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->options['breadcrumb']))
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'), $data)
                ->AddIncludeToRegion('sidebar', $this->LoadView('index_sidebar.tpl.php'), $data);
  }


  /**
   * Display category page.
   *
   * @param string $aCategory the category to display.
   */
  public function Category($aCategory=null) {
    if(is_null($aCategory)) { return $this->Index(); }
    if(!is_slug($aCategory)) { $this->ShowErrorPage(404, t('The category is invalid.')); }
    
    $rssFeeds = new CMRSSAggregator();
    $rssFeeds->Load(array_merge($this->options['aggregator_options'], array('number_of_items'=>3, 
                                                                   'categories_include'=>array($aCategory),
                                                                   'teaser'=>800)));

    if(!isset($rssFeeds['categories'][$aCategory])) { $this->ShowErrorPage(404, t('The category does not exists.')); }

    $data = array(
      'category' => array(
        'title' => $rssFeeds['categories'][$aCategory]['name'],
        'description' => $rssFeeds['categories'][$aCategory]['description'],
      ),
      'feeds' => $rssFeeds->data,
      'categories' => $rssFeeds->GetCategories(),
    );

    $this->options['breadcrumb'][] = array('label' => $rssFeeds['categories'][$aCategory]['name'], 
                                           'url' => $this->CreateUrlToController(null, $aCategory));
    
    $this->views->SetTitle(strtr($this->options['title']['category'], array('@category' => $rssFeeds['categories'][$aCategory]['name'])))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->options['breadcrumb']))
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'), $data)
                ->AddIncludeToRegion('sidebar', $this->LoadView('category_sidebar.tpl.php'), $data);
  }


  /**
   * Display feed page of a single feed.
   *
   * @param string $aFeed the feed to display.
   */
  public function Feed($aFeed=null) {
    if(is_null($aFeed)) { return $this->Index(); }
    if(!is_slug($aFeed)) { $this->ShowErrorPage(404, t('The feed is invalid.')); }

    $rssFeeds = new CMRSSAggregator();
    $rssFeeds->Load(array_merge($this->options['aggregator_options'], array('number_of_items'=>10, 
                                                                   'sites_include'=>array($aFeed),
                                                                   'teaser'=>800)));

    if(!isset($rssFeeds['sites'][$aFeed])) { $this->ShowErrorPage(404, t('The feed does not exists.')); }

    $data = array(
      'feed' => array(
        'title' => $rssFeeds['sites'][$aFeed]->name,
        'description' => $rssFeeds['sites'][$aFeed]->description,
        'siteurl' => $rssFeeds['sites'][$aFeed]->siteurl,
        'feedurl' => $rssFeeds['sites'][$aFeed]->feedurl,
      ),
      'feeds' => $rssFeeds->data,
      'categories' => $rssFeeds->GetCategories(),
      'related' => $rssFeeds->GetRelatedSites($aFeed),
    );

    $this->options['breadcrumb'][] = array('label' => $rssFeeds['sites'][$aFeed]->name, 
                                           'url' => $this->CreateUrlToController(null, $aFeed));
    
    $this->views->SetTitle(strtr($this->options['title']['feed'], array('@feed' => $rssFeeds['sites'][$aFeed]->name)))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this->options['breadcrumb']))
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'), $data)
                ->AddIncludeToRegion('sidebar', $this->LoadView('feed_sidebar.tpl.php'), $data);
  }


}
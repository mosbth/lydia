<?php
/**
 * Presentation of RSS aggregation, all combined, category and per feed.
 *
 * @package: LydiaApplication
 */
class CCAllSeeingEye extends CObject implements IController, ArrayAccess {

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
      'config' => __DIR__ . '/config.php',
      'cache_duration' => 3600*4,
      'title' => array(
        'index' => t('RSS aggregation'),
        'category' => t('@category (RSS aggregation)'),
        'feed' => t('@feed (RSS aggregation)'),
      ),
      'breadcrumb' => array(
        array('label' => t('RSS aggregation'), 'url' => $this->CreateUrlToController()),
      ),
    );
    $this->options = array_merge($default, $options);
  }
  

  /**
   * Implementing ArrayAccess for $this->options
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->options[] = $value; } else { $this->options[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->options[$offset]); }
  public function offsetUnset($offset) { unset($this->options[$offset]); }
  public function offsetGet($offset) { return isset($this->options[$offset]) ? $this->options[$offset] : null; }


  /**
   * Index page.
   */
  public function Index() {
    $rssFeeds = new CMRSSAggregator();
    $rssFeeds->Load(array('feeds_file' => $this['config'], 
                          'cache_duration'=>$this['cache_duration'],
                          'number_of_items'=>1));

    $this->views->SetTitle($this['title']['index'])
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this['breadcrumb']))
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'), array('feeds'=>$rssFeeds->data))
                ->AddIncludeToRegion('sidebar', $this->LoadView('index_sidebar.tpl.php'), array('feeds'=>$rssFeeds->data, 'categories'=>$rssFeeds->GetCategories()));
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
    $rssFeeds->Load(array('feeds_file' => $this['config'],
                          'cache_duration'=>$this['cache_duration'],
                          'number_of_items'=>3, 
                          'categories_include'=>array($aCategory),
                          'teaser'=>800));

    if(!isset($rssFeeds['categories'][$aCategory])) { $this->ShowErrorPage(404, t('The category does not exists.')); }

    $this->options['breadcrumb'][] = array('label' => $rssFeeds['categories'][$aCategory]['name'], 
                                           'url' => $this->CreateUrlToController(null, $aCategory));
    
    $this->views->SetTitle(strtr($this['title']['category'], array('@category' => $rssFeeds['categories'][$aCategory]['name'])))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this['breadcrumb']))
                ->AddIncludeToRegion('primary', $this->LoadView('category.tpl.php'), array('feeds'=>$rssFeeds->data))
                ->AddIncludeToRegion('sidebar', $this->LoadView('category_sidebar.tpl.php'), array('feeds'=>$rssFeeds->data, 'categories'=>$rssFeeds->GetCategories()));
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
    $rssFeeds->Load(array('feeds_file' => $this['config'],
                          'cache_duration'=>$this['cache_duration'],
                          'number_of_items'=>10, 
                          'sites_include'=>array($aFeed),
                          'teaser'=>800));

    if(!isset($rssFeeds['sites'][$aFeed])) { $this->ShowErrorPage(404, t('The feed does not exists.')); }

    $this->options['breadcrumb'][] = array('label' => $rssFeeds['sites'][$aFeed]->name, 
                                           'url' => $this->CreateUrlToController(null, $aFeed));
    
    $this->views->SetTitle(strtr($this['title']['feed'], array('@feed' => $rssFeeds['sites'][$aFeed]->name)))
                ->AddStringToRegion('breadcrumb', $this->CreateBreadcrumb($this['breadcrumb']))
                ->AddIncludeToRegion('primary', $this->LoadView('feed.tpl.php'), array('feeds'=>$rssFeeds->data))
                ->AddIncludeToRegion('sidebar', $this->LoadView('feed_sidebar.tpl.php'), array('feeds'=>$rssFeeds->data, 'categories'=>$rssFeeds->GetCategories(), 'related'=>$rssFeeds->GetRelatedSites($aFeed)));
  }


}
<?php
/**
 * A model for aggregating RSS content.
 * 
 * @package LydiaCMF
 */
class CMRSSAggregator extends CObject implements ArrayAccess {

  /**
   * Properties
   */
  public $data;
  public $feeds;

  /**
   * Constructor
   */
  public function __construct($id=null) { parent::__construct(); }


  /**
   * Implementing ArrayAccess for $this->data
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->data[$offset]); }
  public function offsetUnset($offset) { unset($this->data[$offset]); }
  public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }


  /**
   * Get related sites, which means all feeds in the same category.
   *
   * @param stdObject $siteKey key to a site.
   * @returns array with sites.
   */
  public function GetRelatedSites($siteKey) {
    $related = array();
    foreach($this->feeds['categories'][$this->data['sites'][$siteKey]->category->key]['sites'] as $val) {
      $site = new stdClass();
      $site->key = $val;
      $site->name = $this->feeds['sites'][$val]['name'];
      $site->url = $this->CreateUrl($this->options['url_controller_site'], $this->options['url_method_site'],  $val);
      $related[] = $site;
    }
    return $related;
  }
  
  
  /**
   * Get categories.
   *
   * @returns array with categories.
   */
  public function GetCategories() {
    $categories = array();
    foreach($this->feeds['categories'] as $categoryKey => $categoryConfig) {
      $category = new stdClass();
      $category->key = $categoryKey;
      $category->name = $categoryConfig['name'];
      $category->description = $categoryConfig['description'];
      $category->url = $this->CreateUrl($this->options['url_controller_category'], $this->options['url_method_category'], $categoryKey);
      $category->sites = $categoryConfig['sites'];
      $categories[] = $category;
    }
    return $categories;
  }
  
  
  /**
   * Load all, or specified, RSS feeds.
   *
   * @param array $options set to only load certain categories or sites.
   */
  public function Load($options=array()) {
    $cmpFunction = function($a, $b) { return date($a->date) == date($b->date) ? 0 : ($a->date < $b->date ? 1 : -1); };
    $default = array(
      'config' => __DIR__.'/config.php',
      'categories' => array(),
      'categories_exclude' => array(),
      'categories_include' => array(),
      'feeds' => array(),
      'feeds_exclude' => array(),
      'feeds_include' => array(),
      'number_of_items' => 5,
      'url_controller_category' => null,
      'url_method_category' => 'category',
      'url_controller_site' => null,
      'url_method_site' => 'feed',
      'cache_duration' => 3600,
      'teaser' => 400,
    );
    $options = array_merge($default, $options);

    if(is_readable($options['config'])) include($options['config']);
      else throw new Exception('No such feed file.');
    $this->feeds = $feeds;
    $this->options = $options;

    if(!empty($options['categories_include'])) $options['categories'] = array_intersect_key($feeds['categories'], array_flip($options['categories_include']));
    if(!empty($options['categories_exclude'])) $options['categories'] = array_diff_key($feeds['categories'], array_flip($options['categories_exclude']));
    if(!empty($options['sites_include'])) $options['sites'] = array_intersect_key($feeds['sites'], array_flip($options['sites_include']));
    if(!empty($options['sites_exclude'])) $options['sites'] = array_diff_key($feeds['sites'], array_flip($options['sites_exclude']));
    
    // Load by site or by category depending on options
    if(!empty($options['sites'])) {
      $this->LoadSites($options['sites']);    
    } else {
      if(empty($options['categories'])) {
        $options['categories'] = $this->feeds['categories'];
      }
      $this->data['categories'] = $options['categories'];
      foreach($this->data['categories'] as $categoryKey => $category) {
        $items = $this->LoadSites(array_flip($category['sites']));    
        usort($items, $cmpFunction);
        $this->data['categories'][$categoryKey]['items'] = $items;
      }
    }
    if(isset($this->data['items'])) {
      usort($this->data['items'], $cmpFunction);
    }

    // Load category data into sites
    foreach($this->feeds['categories'] as $categoryKey => $categoryConfig) {
      $category = new stdClass();
      $category->key = $categoryKey;
      $category->name = $categoryConfig['name'];
      $category->description = $categoryConfig['description'];
      $category->url = $this->CreateUrl($options['url_controller_category'], $options['url_method_category'], $categoryKey);
      $category->sites = $categoryConfig['sites'];
      foreach($categoryConfig['sites'] as $site) {
        if(isset($this->data['sites'][$site])) {
          $this->data['sites'][$site]->category = $category;
        }
      }
    }
  }


  /**
   * Load feed from specified sites.
   *
   * @param array $sites with list of sites to load.
   */
  private function LoadSites($sites) {
    $rss = CRSSFeed::Factory('simplepie', $this->options['cache_duration']);
    $items = array();
    foreach($sites as $siteKey => $siteConfig) {
      $siteConfig = $this->feeds['sites'][$siteKey];
      $site = new stdClass();
      $site->key = $siteKey;
      $site->name = $siteConfig['name'];
      $site->feedurl = $siteConfig['feedurl'];
      $site->url = $this->CreateUrl($this->options['url_controller_site'], $this->options['url_method_site'],  $siteKey);
    
      $rss->set_feed_url($site->feedurl);
      $rss->init();
      $rss->handle_content_type();

      $site->permalink = $rss->get_permalink();
      $site->siteurl = $rss->get_permalink();
      $site->title = $rss->get_title();
      $site->description = strip_tags($rss->get_description());
      $this->data['sites'][$siteKey] = $site;

      $i=0;
      foreach($rss->get_items() as $item) {
        $entry = new stdClass();
        $entry->site = $site;
        $entry->permalink = $item->get_permalink();
        $entry->title = strip_tags($item->get_title());
        $entry->date = $item->get_date('c');
        $entry->content = teaser(CTextFilter::Purify(strip_tags($item->get_content())), $this->options['teaser']);
        
        // Add shallow copy in several places to ease usage and presentation
        $this->data['sites'][$siteKey]->items[] = $entry;
        $this->data['items'][] = $entry;
        $items[] = $entry;
        if(++$i >= $this->options['number_of_items']) break;
      }
    }
    return $items;
  }


}
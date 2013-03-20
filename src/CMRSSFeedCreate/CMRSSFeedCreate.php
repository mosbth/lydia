<?php
/**
 * Creating RSS feed and caching them.
 * 
 * @package LydiaCore
 */
class CMRSSFeedCreate extends CObject implements IModule {

  /**
   * Constructor
   *
   * @param array $options to configure its look and feel.
   */
  public function __construct($options=array()) {
    parent::__construct();

    $default = array(
      'encoding'    => $this->config['character_encoding'],
      'title'       => t('RSS feed'),
      'link'        => $this->CreateUrlToController(),
      'description' => t('This is an example RSS feed'),
      'language'    => $this->config['language'],
      'copyright'   => $this->config['feed']['copyright'],
      'self'        => $this->CreateUrlToControllerMethod(),

      'cache_age'   => 60*5,
      'cache_pre'   => 'cache_',
      'cache_post'  => '.xml',
    );
    $this->options = array_merge($default, $options);
  }


  /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) { require_once(__DIR__.'/CMRSSFeedCreateModule.php'); $m = new CMRSSFeedCreateModule(); return $m->Manage($action); }



  /**
   * Get name for the file in the cache.
   *
   * @param string $key the unique key fo the feed.
   * @param array $items for the feed.
   */
  public function CreateFeed($key, $items=array()) {
    $file = $this->CacheName($key);
    $xml = null;
    foreach($items as $val) {
      $xml .= <<<EOD
    <item>
      <title>{$val['title']}</title>
      <description>{$val['description']}</description>
      <link>{$val['link']}</link>
      <guid>{$val['guid']}</guid>
      <pubDate>{$val['pubdate']}</pubDate>
    </item>\n
EOD;
    }

    $o = $this->options;
    $feed = <<< EOD
<?xml version="1.0" encoding="{$o['encoding']}"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>{$o['title']}</title>
    <link>{$o['link']}</link>
    <description>{$o['description']}</description>
    <language>{$o['language']}</language>
    <copyright>{$o['copyright']}</copyright>
    <atom:link href="{$o['self']}" rel="self" type="application/rss+xml" />
{$xml}
  </channel>
</rss>\n
EOD;

    if(file_put_contents($file, $feed) === false) {
      throw new Exception(t('Failed to write cache object: !file'), array('!file' => $file));
      
    }
  }
 


  /**
   * Get name for the file in the cache.
   *
   * @param string $key the unique key fo the feed.
   */
  public function CacheName($key) {
    return CMModules::GetModuleDirectory(get_class()) . '/' . $this->options['cache_pre'] . slugify($key) . $this->options['cache_post'];
  }
 


  /**
   * Check if there is a valid file in the cache.
   *
   * @param string $key the unique key fo the feed.
   */
  public function HasValidCache($key) {
    $file = $this->CacheName($key);
    $modified = null;
    if(is_file($file)) {
      $modified = filemtime($file);
    } 
    return ($modified + $this->options['cache_age'] > time());
  }
 


  /**
   * Get the feed from cache.
   *
   * @param string $key the unique key fo the feed.
   */
  public function ReadFeedAndExit($key) {
    $file = $this->CacheName($key);
    $time = is_file($file) ? filemtime($file) : null;

    // Write it out and leave a response
    if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $time){  
      header("HTTP/1.0 304 Not Modified");  
    } else {  
      header('Content-type: application/rss+xml; charset=' . $this->options['encoding']);  
      header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $time) . " GMT");  
      readfile($file);
    }
    exit();
  }
 


}

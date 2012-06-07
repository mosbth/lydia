<?php
/**
 * A wrapper for various text filtering options.
 * 
 * @package LydiaCore
 */
class CTextFilter {

  /**
   * Properties
   */
  public static $purify = null;


  /**
   * Clean your HTML with HTMLPurifier, create an instance of HTMLPurifier if it does not exists. 
   *
   * @param $text string the dirty HTML.
   * @returns string as the clean HTML.
   */
   public static function Purify($text) {   
    if(!self::$purify) {
      require_once(__DIR__.'/htmlpurifier-4.4.0-standalone/HTMLPurifier.standalone.php');
      $config = HTMLPurifier_Config::createDefault();
      $config->set('Cache.DefinitionImpl', null);
      self::$purify = new HTMLPurifier($config);
    }
    return self::$purify->purify($text);
  }
  
  
  /**
   * Support Markdown syntax with PHP Markdown. 
   *
   * @param $text string with Markdown text.
   * @returns string as formatted HTML.
   */
   public static function Markdown($text) {   
    require_once(__DIR__.'/php-markdown/markdown.php');
    return Markdown($text);
  }
  

  /**
   * Support SmartyPants for better typography. 
   *
   * @param string text text to be converted.
   * @returns string the formatted text.
   */
   public static function SmartyPants($text) {   
    require_once(__DIR__.'/php_smartypants_1.5.1e/smartypants.php');
    return SmartyPants($text);
  }
  

  /**
   * Support enhanced SmartyPants/Typographer for better typography. 
   *
   * @param string text text to be converted.
   * @returns string the formatted text.
   */
   public static function Typographer($text) {   
    require_once(__DIR__.'/php_smartypants_typographer_1.0/smartypants.php');
    return SmartyPants($text);
  }
  

  /**
   * BBCode formatting converting to HTML.
   *
   * @param string text text to be converted.
   * @returns string the formatted text.
   */
  public static function Bbcode2HTML($text) {
    $search = array( 
      '/\[b\](.*?)\[\/b\]/is', 
      '/\[i\](.*?)\[\/i\]/is', 
      '/\[u\](.*?)\[\/u\]/is', 
      '/\[img\](https?.*?)\[\/img\]/is', 
      '/\[url\](https?.*?)\[\/url\]/is', 
      '/\[url=(https?.*?)\](.*?)\[\/url\]/is' 
      );   
    $replace = array( 
      '<strong>$1</strong>', 
      '<em>$1</em>', 
      '<u>$1</u>', 
      '<img src="$1" />', 
      '<a href="$1">$1</a>', 
      '<a href="$1">$2</a>' 
      );     
    return preg_replace($search, $replace, $text);
  }


  /**
   * Make clickable links from URLs in text.
   *
   * @param string text  text to be converted.
   * @returns string the formatted text.
   */
  public static function MakeClickable($text) {
    return preg_replace_callback(
      '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', 
      create_function(
        '$matches',
        'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
      ),
      $text
    );
  }


}
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
  public static $geshi = null;


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
   * Support Markdown syntax with PHP Markdown Extra. 
   *
   * @param $text string with Markdown text.
   * @returns string as formatted HTML.
   */
   public static function MarkdownExtra($text) {   
    require_once(__DIR__.'/php_markdown_extra_1.2.5/markdown.php');
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
   * Syntax highlighter using GeSHi http://qbnz.com/highlighter/. 
   *
   * @param string $text text to be converted.
   * @param string $language which language to use for highlighting syntax.
   * @returns string the formatted text.
   */
   public static function SyntaxHighlightGeSHi($text, $language) {   
    if(!self::$geshi) {
      require_once(__DIR__.'/geshi/geshi.php');
      //$path = 'geshi/geshi';
      //$geshi = new GeSHi($text, $language, $path);
      self::$geshi = new GeSHi($text, $language);
      //$geshi->enable_classes();
      self::$geshi->set_overall_class('geshi');
      //self::$geshi->set_header_type(GESHI_HEADER_PRE_TABLE);
      //self::$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
      //CLydia::Instance()->views->AddStyle($geshi->get_stylesheet());
    }
    self::$geshi = new GeSHi($text, $language);
    self::$geshi->set_overall_class('geshi');
    return self::$geshi->parse_code();
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
   * @param string text text to be converted.
   * @returns string the formatted text.
   */
  public static function MakeClickable($text) {
    return preg_replace_callback(
      '#\b(?<!href=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
     // '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', 
      create_function(
        '$matches',
        'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
      ),
      $text
    );
  }


  /**
   * Shorttags to to quicker format text as HTML.
   *
   * @param string text text to be converted.
   * @returns string the formatted text.
   */
  public static function ShortTags($text) {
    $callback = function($matches) {
      switch($matches[1]) {
        case 'IMG':
          $caption = t('Image: ');
          return <<<EOD
<figure>
  <a href='{$matches[2]}'><img src='{$matches[2]}' alt='{$matches[3]}' /></a>
  <figcaption>{$caption}{$matches[3]}</figcaption>
</figure>
EOD;
        break;
        
        //case 'syntax=': return CTextFilter::SyntaxHighlightGeSHi($matches[3], $matches[2]); break;
        case 'syntax=': return "<pre>" . highlight_string($matches[3], true) . "</pre>"; break;
        //case 'INCL':  include($matches[2]); break;
        case 'INFO':  return "<div class='info'>"; break;
        case '/INFO': return "</div>"; break;
        default: return "{$matches[1]} IS UNKNOWN SHORTTAG."; break;
      }
    };
    $patterns = array(
      '/\[(IMG) src=(.+) alt=(.+)\]/',
      '/~~~(syntax=)(php|html|css|sql|javascript)\n([^~]+)\n~~~/s',
      //'/\[(INCL)/s*([^\]+)/',
      '#\[(INFO)\]#', '#\[(/INFO)\]#',
    );
    return preg_replace_callback($patterns, $callback, $text);
  }


}
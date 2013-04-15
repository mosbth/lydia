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
      require_once(__DIR__.'/geshi_1.0.8.10/geshi.php');
      //$path = 'geshi/geshi';
      //$geshi = new GeSHi($text, $language, $path);
      //self::$geshi = new GeSHi($text, $language);
      //$geshi->enable_classes();
      //self::$geshi->set_overall_class('geshi');
      //self::$geshi->set_header_type(GESHI_HEADER_PRE_TABLE);
      //self::$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
      //self::$geshi->set_line_style('color: red; font-weight: bold;', 'color: green;');
      //CLydia::Instance()->views->AddStyle($geshi->get_stylesheet());
    }
    $language = ($language === 'html') ? 'html4strict' : $language;
    self::$geshi = new GeSHi($text, $language);
    self::$geshi->set_overall_class('geshi');
    self::$geshi->enable_classes('geshi');
    //self::$geshi->set_header_type(GESHI_HEADER_PRE_VALID);
    //self::$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
    //echo "<pre>", self::$geshi->get_stylesheet(false) , "</pre>";
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
      '#\b(?<![href|src]=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
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
          $pos = strpos($matches[2], '?');
          $href = $pos ? substr($matches[2], 0, $pos) : $matches[2];
          $src = htmlspecialchars($matches[2]);
          return <<<EOD
<figure>
  <a href='{$href}'><img src='{$src}' alt='{$matches[3]}' /></a>
  <figcaption markdown=1>{$caption}{$matches[3]}</figcaption>
</figure>
EOD;

        case 'IMG2':
          $caption = t('Image: ');
          $pos = strpos($matches[2], '?');
          $href = $pos ? substr($matches[2], 0, $pos) : $matches[2];
          $src = htmlspecialchars($matches[2]);
          return <<<EOD
<figure class="{$matches[4]}">
  <a href='{$href}'><img src='{$src}' alt='{$matches[3]}' /></a>
  <figcaption markdown=1>{$caption}{$matches[3]}</figcaption>
</figure>
EOD;

/*        case 'AUTHOR':
          $name   = $matches[2];
          $email  = $matches[3];
          // Should use ALTERNATE URL from config.php
          $href   = " href='" . CLydia::Instance()->CreateUrl('author', $matches[4]) . "'";
          $text   = t('By @NAME (@EMAIL)', array('@NAME' => $name, '@EMAIL' => $email));
          return "<span class='author-inline'><a{$href}>{$text}</a></span>\n";
        break;
*/
        case 'BOOK':
          $isbn = $matches[2];
          $stores = array(
            'BTH' => "http://bth.summon.serialssolutions.com/search/results?spellcheck=true&q={$isbn}",
            'Libris' => "http://libris.kb.se/hitlist?q={$isbn}",
            'Google Books' => "http://books.google.com/books?q={$isbn}",
            'Bokus' => "http://www.bokus.com/bok/{$isbn}",
            'Adlibris' => "http://www.adlibris.com/se/product.aspx?isbn={$isbn}",
            'Amazon' => "http://www.amazon.com/s/ref=nb_ss?url=field-keywords={$isbn}",
            'Barnes&Noble' => "http://search.barnesandnoble.com/booksearch/ISBNInquiry.asp?r=1&IF=N&EAN={$isbn}",
          );
          $html = null;
          foreach($stores as $key => $val) {
            $html .= "<a href='$val'>$key</a> &bull; ";
          }
          return substr($html, 0, -8);
        break;

        case 'YOUTUBE':
          $caption = t('Figure: ');
          $height = ceil($matches[3] / (16/9));
          return <<<EOD
<figure>
  <iframe width='{$matches[3]}' height='{$height}' src="http://www.youtube.com/embed/{$matches[2]}" frameborder="0" allowfullscreen></iframe>
  <figcaption>{$caption}{$matches[4]}</figcaption>
</figure>
EOD;
        break;
        
        case 'syntax=': return CTextFilter::SyntaxHighlightGeSHi($matches[3], $matches[2]); break;
        case '```': return CTextFilter::SyntaxHighlightGeSHi($matches[3], $matches[2]); break;
        //case 'syntax=': return "<pre>" . highlight_string($matches[3], true) . "</pre>"; break;
        //case 'INCL':  include($matches[2]); break;
        case 'INFO':  return "<div class='info' markdown=1>"; break;
        case '/INFO': return "</div>"; break;
        case 'BASEURL': return CLydia::Instance()->request->base_url; break;
        default: return "{$matches[1]} IS UNKNOWN SHORTTAG."; break;
      }
    };
    $patterns = array(
      '#\[(BASEURL)\]#',
      //'/\[(AUTHOR) name=(.+) email=(.+) url=(.+)\]/',
      '/\[(IMG) src=(.+) alt=(.+)\]/',
      '/\[(IMG2) src=(.+) alt="(.+)" class="(.+)"\]/',
      '/\[(BOOK) isbn=(.+)\]/',
      '/\[(YOUTUBE) src=(.+) width=(.+) caption=(.+)\]/',
      '/~~~(syntax=)(php|html|html5|css|sql|javascript|bash)\n([^~]+)\n~~~/s',
      '/(```)(php|html|html5|css|sql|javascript|bash)\n([^`]+)\n```/s',
      //'/\[(INCL)/s*([^\]+)/',
      '#\[(INFO)\]#', '#\[(/INFO)\]#',
    );
    return preg_replace_callback($patterns, $callback, $text);
  }


}
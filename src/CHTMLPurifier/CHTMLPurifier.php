<?php
/**
 * A wrapper for HTMLPurifier by Edward Z. Yang, http://htmlpurifier.org/
 * 
 * @package LydiaCore
 */
class CHTMLPurifier {

  /**
   * Properties
   */
  public static $instance = null;


  /**
   * Purify it. Create an instance of HTMLPurifier if it does not exists. 
   *
   * @param $text string the dirty HTML.
   * @returns string as the clean HTML.
   */
   public static function Purify($text) {   
    if(!self::$instance) {
      require_once(__DIR__.'/htmlpurifier-4.4.0-standalone/HTMLPurifier.standalone.php');
      $config = HTMLPurifier_Config::createDefault();
      $config->set('Cache.DefinitionImpl', null);
      self::$instance = new HTMLPurifier($config);
    }
    return self::$instance->purify($text);
  }
  
  
}
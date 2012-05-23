<?php
/**
 * A factory wrapper for creating  objects of external RSS libs.
 * 
 * @package LydiaCMF
 */
class CRSSFeed implements IModule {

  /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) {
    switch($action) {
      case 'install':
        $cache = LYDIA_SITE_PATH.'/data/crssfeed';
        if(!is_dir($cache)) {
          if(!mkdir($cache)) {
            return array('error', 'Could not create cache-directory.');
          } else {
            return array('success', 'Created cache-directory.');        
          }          
        } else {
          return array('success', 'Cache-directory is already existing.');        
        }
      break;
      
      default:
        throw new Exception('Unsupported action for this module.');
      break;
    }
  }

 
  /**
   * Creating an object of supported RSS software.
   *
   * @param string $type which object to create.
   * @param integer $cacheDuration seconds before the cache is updated.
   * @returns object of the choosen type.
   */
  public static function Factory($type, $cacheDuration=3600) {
    $cache = LYDIA_SITE_PATH.'/data/crssfeed';
    switch($type) {
      case 'simplepie':
        require_once(__DIR__."/simplepie/SimplePie.compiled.php");    
        $object = new SimplePie();
        $object->set_cache_location($cache);
        $object->set_cache_duration($cacheDuration);
        return $object;
      break;
      
      default:
        throw new Exception('Unsupported type.');
      break;
    }
  }

 
}
<?php
/**
 * To manage the module.
 * 
 * @package LydiaCMF
 */
class CRSSFeedModule {

  /**
   * Manage install/update/deinstall and equal actions.
   */
  public static function Manage($action=null) {
    switch($action) {
      case 'install':
        $cache = LYDIA_SITE_PATH.'/data/crssfeed';
        if(!is_dir($cache)) {
          if(!mkdir($cache)) {
            return array('error', t('Could not create cache-directory.'));
          } else {
            return array('success', t('Created cache-directory.'));
          }          
        } else {
          return array('success', t('Cache-directory is already existing.'));
        }
      break;
      
      case 'supported-actions':
        $actions = array('install');
        return array('success', t('Supporting the following actions: !actions.', array('!actions'=>implode(', ', $actions))), 'actions'=>$actions);
      break;

      default:
        return array('info', t('Action not supported by this module.'));
      break;
    }
  }

 
}
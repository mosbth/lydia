<?php
/**
 * To manage the module.
 * 
 * @package LydiaCMF
 */
class CRSSFeedModule extends CRSSFeed {

  /**
   * Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) {
    switch($action) {
      case 'install':
        $ret = CMModules::CreateModuleDirectory(get_parent_class());
        switch($ret) {
          case null:  return array('success', t('Cache-directory is already existing and is left untouched.')); break;
          case false: return array('error', t('Could not create cache-directory.')); break;
          default:    return array('success', t('Created cache-directory.')); break;
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


/*
        $ret = CMModules::CreateModuleDirectory(get_parent_class(), 'txt');
        $status = 'success';
        $msg = t('Successfully created the database tables.');
        if($ret === null) {
          $msg .= ' '.t('Directory in site/data already exists.');
        } elseif($ret === false) {
          $status = 'error';
          $msg .= ' '.t('Failed to create directory in site/data.');
        } else {
          $msg .= ' '.t('Created directory in site/data.');
        }
        return array($status, $msg);

        */
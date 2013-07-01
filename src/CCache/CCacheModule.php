<?php
/**
 * To manage the module.
 */
class CCacheModule extends CCache {

  /**
   * Manage install/update/deinstall and equal actions.
   *
   * @param string $action the action to carry out.
   * @return array with status information on the actions.
   */
  public function Manage($action=null) {
    switch($action) {
      case 'install': 
        $ret = CMModules::CreateModuleDirectory(get_parent_class());
        $status = 'success';
        if($ret === null) {
          $msg = ' '.t('Directory in site/data already exists.');
        } elseif($ret === false) {
          $status = 'error';
          $msg = ' '.t('Failed to create directory in site/data.');
        } else {
          $msg = ' '.t('Created directory in site/data.');
        }
        return array($status, $msg);
      break;
      
      case 'prune-cache':
        $cache = new CCache();
        $items = $cache->PruneAll();
        $status = 'success';
        $msg = t('Successfully removed !NUMBER entries from the cache.', array('!NUMBER'=>$items));
        return array($status, $msg);
      break;
      
      case 'supported-actions':
        $actions = array('install', 'prune');
        return array('success', t('Supporting the following actions: !actions.', array('!actions'=>implode(', ', $actions))), 'actions'=>$actions);
      break;

      default:
        return array('info', t('Action not supported by this module.'));
      break;
    }
  }


}

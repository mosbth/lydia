<?php
/**
 * To manage the module.
 */
class CMRSSFeedCreateModule extends CMRSSFeedCreate {

  /**
   * Manage install/update/deinstall and equal actions.
   *
   * @param string $action the action to carry out.
   */
  public function Manage($action=null) {
    switch($action) {
      case 'install': 
        $ret = CMModules::CreateModuleDirectory(get_parent_class());
        $status = 'success';
        $msg = null;
        if($ret === null) {
          $msg = t('Directory in site/data already exists.');
        } elseif($ret === false) {
          $status = 'error';
          $msg = t('Failed to create directory in site/data.');
        } else {
          $msg = t('Created directory in site/data.');
        }
        return array($status, $msg);
      break;


      case 'prune-cache': 
        $dir = CMModules::GetModuleDirectory(get_parent_class());
        $files = glob("{$dir}/{$this->options['cache_pre']}*");
        $i = 0;
        foreach($files as $file) {
          unlink($file);
          $i++;
        }

        $status = 'success';
        $msg = t('Removed !num objects from cache', array('!num' => $i));
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

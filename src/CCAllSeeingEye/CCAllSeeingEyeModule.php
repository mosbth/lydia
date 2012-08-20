<?php
/**
 * To manage the module.
 */
class CCAllSeeingEyeModule {

  /**
   * Manage install/update/deinstall and equal actions.
   *
   * @param string $action the action to carry out.
   * @param array $options to use when loading the feed.
   * @returns array with info on success or not.
   */
  public static function Manage($action, $options) {
    switch($action) {
      case 'crontab':
        $object = new CMRSSAggregator();
        $object->Load(array_merge($options, array('cache_duration'=>3600)));
        return array('success', t('Updated cache with new feeds where appropriate.'));
      break;
      
      case 'supported-actions':
        $actions = array('crontab');
        return array('success', t('Supporting the following actions: !actions.', array('!actions'=>implode(', ', $actions))), 'actions'=>$actions);
      break;

      default:
        return array('info', t('Action not supported by this module.'));
      break;
    }
  }

 
}
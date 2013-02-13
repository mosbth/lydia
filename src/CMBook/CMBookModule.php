<?php
/**
 * To manage the module.
 */
class CMBookModule extends CMBook {

  /**
   * Manage install/update/deinstall and equal actions.
   *
   * @param string $action the action to carry out.
   */
  public function Manage($action=null) {
    switch($action) {
      case 'install': 
        $this->db->ExecuteQuery(self::SQL('create table book'));
        $this->db->ExecuteQuery(self::SQL('create table chapter'));
        return array('success', t('Successfully created the database tables.'));
      break;

      case 'export-db':
        $manager = new CMModules();
        $sql  = "-- #### Start Module " . get_parent_class() . "\n";
        $sql .= $manager->DumpTableToSQL(self::SQL('table name book'), self::SQL('export table book'), self::SQL('create table book'), self::SQL('drop table book'));
        $sql .= "-- #### End Module " . get_parent_class() . "\n\n";
        return array('success', t('Successfully exported data as SQL INSERT commands.'), $sql);
      break;
      
        return array('success', t('Successfully dropped and created the database tables.'));
      break;

      case 'supported-actions':
        $actions = array('install', 'export-db');
        return array('success', t('Supporting the following actions: !actions.', array('!actions'=>implode(', ', $actions))), 'actions'=>$actions);
      break;

      default:
        return array('info', t('Action not supported by this module.'));
      break;
    }
  }


}
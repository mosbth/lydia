<?php
/**
 * To manage the module.
 */
class CMUserModule extends CMUser {

  /**
   * Manage install/update/deinstall and equal actions.
   *
   * @param string $action the action to carry out.
   */
  public function Manage($action=null) {
    switch($action) {
      case 'install': 
        $this->db->ExecuteQuery(self::SQL('drop table user2group'));
        $this->db->ExecuteQuery(self::SQL('drop table group'));
        $this->db->ExecuteQuery(self::SQL('drop table user'));
        $this->db->ExecuteQuery(self::SQL('create table user'));
        $this->db->ExecuteQuery(self::SQL('create table group'));
        $this->db->ExecuteQuery(self::SQL('create table user2group'));
        $this->db->ExecuteQuery(self::SQL('insert into user'), array('anonymous', 'Anonymous user', null, 'plain', null, null));
        $password = $this->CreatePassword('root');
        $this->db->ExecuteQuery(self::SQL('insert into user'), array('root', 'The Administrator', 'root@dbwebb.se', $password['algorithm'], $password['salt'], $password['password']));
        $idRootUser = $this->db->LastInsertId();
        $password = $this->CreatePassword('doe');
        $this->db->ExecuteQuery(self::SQL('insert into user'), array('doe', 'John/Jane Doe', 'doe@dbwebb.se', $password['algorithm'], $password['salt'], $password['password']));
        $idDoeUser = $this->db->LastInsertId();
        $this->db->ExecuteQuery(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
        $idAdminGroup = $this->db->LastInsertId();
        $this->db->ExecuteQuery(self::SQL('insert into group'), array('user', 'The User Group'));
        $idUserGroup = $this->db->LastInsertId();
        $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
        $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
        $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idDoeUser, $idUserGroup));
        return array('success', t('Successfully created the database tables and created a default admin user as root:root and an ordinary user as doe:doe.'));
      break;
      
      case 'export-db':
        $manager = new CMModules();
        $sql  = "-- #### Start Module " . get_parent_class() . "\n";
        $sql .= $manager->DumpTableToSQL(self::SQL('table name user'), self::SQL('export table user'), self::SQL('create table user'), self::SQL('drop table user'));
        $sql .= $manager->DumpTableToSQL(self::SQL('table name group'), self::SQL('export table group'), self::SQL('create table group'), self::SQL('drop table group'));
        $sql .= $manager->DumpTableToSQL(self::SQL('table name user2group'), self::SQL('export table user2group'), self::SQL('create table user2group'), self::SQL('drop table user2group'));
        $sql .= "-- #### End Module " . get_parent_class() . "\n\n";
        return array('success', t('Successfully exported data as SQL INSERT commands.'), $sql);
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


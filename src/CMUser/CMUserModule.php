<?php
/**
 * To manage the module.
 */
class CMUserModule extends CMUser {

  /**
   * Manage install/update/deinstall and equal actions.
   *
   * @param string $action the action to carry out.
   * @param array $args extra arguments.
   */
  public function Manage($action=null, $args=null) {
    switch($action) {
      case 'install-root': 

        // Need to have arguments to create root user
        if(!is_array($args)) {
          return array('error', t('CMUserModule::Manage() says - Missing arguments to create the root user'));
        }
        $rootEmail = $args['rootEmail'];
        $rootUserName = $args['rootUserName'];
        $rootPassword = $args['rootPassword'];
        $password = $this->CreatePassword($rootPassword);

        // Create the tables if not already there
        $this->db->ExecuteQuery(self::SQL('create table user'));
        $this->db->ExecuteQuery(self::SQL('create table group'));
        $this->db->ExecuteQuery(self::SQL('create table user2group'));

        // Root user already exists?
        $this->db->ExecuteQuery(self::SQL('select user by id'), array(1));
        if($this->db->RowCount()) {
          return array('error', t('You can not create a root user since there is already a root-user with id=1.'));
        }

        $this->db->ExecuteQuery(self::SQL('insert into user'), array($rootUserName, 'The Root User', $rootEmail, $password['algorithm'], $password['salt'], $password['password']));
        $idRootUser = $this->db->LastInsertId();
        $this->db->ExecuteQuery(self::SQL('insert into user'), array('anonymous', 'Anonymous user', null, 'plain', null, null));
        $this->db->ExecuteQuery(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
        $idAdminGroup = $this->db->LastInsertId();
        $this->db->ExecuteQuery(self::SQL('insert into group'), array('user', 'The User Group'));
        $idUserGroup = $this->db->LastInsertId();
        $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
        $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
        return array('success', t('Database tables for users and groups are ready, as is the the root user.'));
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
        $actions = array('install-root', 'export-db');
        return array('success', t('Supporting the following actions: !actions.', array('!actions'=>implode(', ', $actions))), 'actions'=>$actions);
      break;

      default:
        return array('info', t('Action not supported by this module.'));
      break;
    }
  }


}


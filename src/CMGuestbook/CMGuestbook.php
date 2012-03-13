<?php
/**
 * A model for a guestbok, to show off some basic controller & model-stuff.
 * 
 * @package LydiaCore
 */
class CMGuestbook extends CObject implements IHasSQL {


  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }


  /**
   * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
   *
   * @param string $key the string that is the key of the wanted SQL-entry in the array.
   */
  public static function SQL($key=null) {
    $queries = array(
      'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
      'insert into guestbook'   => 'INSERT INTO Guestbook (entry) VALUES (?);',
      'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
      'delete from guestbook'   => 'DELETE FROM Guestbook;',
     );
    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }


  /**
   * Init the guestbook and create appropriate tables.
   */
  public function Init() {
    try {
      $this->db->ExecuteQuery(self::SQL('create table guestbook'));
      $this->session->AddMessage('notice', 'Successfully created the database tables (or left them untouched if they already existed).');
    } catch(Exception$e) {
      die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
    }
  }
  

  /**
   * Add a new entry to the guestbook and save to database.
   */
  public function Add($entry) {
    $this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry));
    $this->session->AddMessage('success', 'Successfully inserted new message.');
    if($this->db->rowCount() != 1) {
      die('Failed to insert new guestbook item into database.');
    }
  }
  

  /**
   * Delete all entries from the guestbook and database.
   */
  public function DeleteAll() {
    $this->db->ExecuteQuery(self::SQL('delete from guestbook'));
    $this->session->AddMessage('info', 'Removed all messages from the database table.');
  }
  
  
  /**
   * Read all entries from the guestbook & database.
   */
  public function ReadAll() {
    try {
      return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook'));
    } catch(Exception $e) {
      return array();    
    }
  }

  
} 
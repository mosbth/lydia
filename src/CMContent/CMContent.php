<?php
/**
 * A model for content stored in database.
 * 
 * @package LydiaCore
 */
class CMContent extends CObject implements IHasSQL, ArrayAccess {

  /**
   * Properties
   */
  public $data;


  /**
   * Constructor
   */
  public function __construct($id=null) {
    parent::__construct();
    if($id) {
      $this->LoadById($id);
    } else {
      $this->data = array();
    }
  }


  /**
   * Implementing ArrayAccess for $this->data
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->data[$offset]); }
  public function offsetUnset($offset) { unset($this->data[$offset]); }
  public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }


  /**
   * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
   *
   * @param $key string the string that is the key of the wanted SQL-entry in the array.
   * @args $args array with arguments to make the SQL queri more flexible.
   * @returns string.
   */
  public static function SQL($key=null, $args=null) {
    $order_order  = isset($args['order-order']) ? $args['order-order'] : 'ASC';
    $order_by     = isset($args['order-by'])    ? $args['order-by'] : 'id';    
    $queries = array(
      'drop table content'      => "DROP TABLE IF EXISTS Content;",
      'create table content'    => "CREATE TABLE IF NOT EXISTS Content (id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, title TEXT, data TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
      'insert content'          => 'INSERT INTO Content (key,type,title,data,idUser) VALUES (?,?,?,?,?);',
      'select * by id'          => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=?;',
      'select * by key'         => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.key=?;',
      'select * by type'        => "SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? ORDER BY {$order_by} {$order_order};",
      'select *'                => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id;',
      'update content'          => "UPDATE Content SET key=?, type=?, title=?, data=?, updated=datetime('now') WHERE id=?;",
     );
    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }


  /**
   * Init the database and create appropriate tables.
   */
  public function Init() {
    try {
      $this->db->ExecuteQuery(self::SQL('drop table content'));
      $this->db->ExecuteQuery(self::SQL('create table content'));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', 'This is a demo post.', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-again', 'post', 'Hello World Again', 'This is another demo post.', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 'Hello World Once More', 'This is one more demo post.', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('home', 'page', 'Home page', 'This is a demo page, this could be your personal home-page.', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('about', 'page', 'About page', 'This is a demo page, this could be your personal about-page.', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('download', 'page', 'Download page', 'This is a demo page, this could be your personal download-page.', $this->user['id']));
      $this->AddMessage('success', 'Successfully created the database tables and created a default "Hello World" blog post, owned by you.');
    } catch(Exception$e) {
      die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
    }
  }
  

  /**
   * Save content. If it has a id, use it to update current entry or else insert new entry.
   *
   * @returns boolean true if success else false.
   */
  public function Save() {
    $msg = null;
    if($this['id']) {
      $this->db->ExecuteQuery(self::SQL('update content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['id']));
      $msg = 'update';
    } else {
      $this->db->ExecuteQuery(self::SQL('insert content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this->user['id']));
      $this['id'] = $this->db->LastInsertId();
      $msg = 'created';
    }
    $rowcount = $this->db->RowCount();
    if($rowcount) {
      $this->AddMessage('success', "Successfully {$msg} content '" . htmlEnt($this['key']) . "'.");
    } else {
      $this->AddMessage('error', "Failed to {$msg} content '" . htmlEnt($this['key']) . "'.");
    }
    return $rowcount === 1;
  }
    

  /**
   * Load content by id.
   *
   * @param $id integer the id of the content.
   * @returns boolean true if success else false.
   */
  public function LoadById($id) {
    $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by id'), array($id));
    if(empty($res)) {
      $this->AddMessage('error', "Failed to load content with id '$id'.");
      return false;
    } else {
      $this->data = $res[0];
    }
    return true;
  }
  
  
  /**
   * List all content.
   *
   * @param $args array with various settings for the request. Default is null.
   * @returns array with listing or null if empty.
   */
  public function ListAll($args=null) {    
    try {
      if(isset($args) && isset($args['type'])) {
        return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by type', $args), array($args['type']));
      } else {
        return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select *', $args));
      }
    } catch(Exception $e) {
      echo $e;
      return null;
    }
  }
  
  
}

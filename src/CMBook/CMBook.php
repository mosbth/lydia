<?php
/**
 * A model for book wrapping content articles.
 * 
 * @package LydiaCore
 */
class CMBook extends CObject implements IHasSQL, ArrayAccess, IModule {

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
    $this->data['chapter'] = null;
    $this->data['chapters'] = array();    
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
   * @returns string with the sql.
   */
  public static function SQL($key=null) {
    $queries = array(
      'table name book'         => "Book",
      'export table book'       => "SELECT * FROM Book;",
      'drop table book'         => "DROP TABLE IF EXISTS Book;",
      'drop table chapter'      => "DROP TABLE IF EXISTS Chapter;",
      'create table book'       => "CREATE TABLE IF NOT EXISTS Book (id INTEGER PRIMARY KEY, key TEXT KEY, title TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, published DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
      'create table chapter'    => "CREATE TABLE IF NOT EXISTS Chapter (id INTEGER PRIMARY KEY, position INTEGER, idBook INTEGER, idContent INTEGER published DATETIME default NULL, FOREIGN KEY(idBook) REFERENCES Book(id), FOREIGN KEY(idContent) REFERENCES Content(id));",
      'insert book'             => 'INSERT INTO Book (key,title,idUser) VALUES (?,?,?);',
      'insert chapter'          => 'INSERT INTO Chapter (position,idBook,idContent) VALUES (?,?,?);',
      'update book'             => "UPDATE Book SET key=?, title=?, updated=datetime('now') WHERE id=?;",
      'update chapter'          => "UPDATE Chapter SET position=?, idContent=? WHERE id=?;",
      'update book as published' => "UPDATE Book SET published=datetime('now') WHERE id=?;",
      'update book as deleted'  => "UPDATE Book SET deleted=datetime('now') WHERE id=?;",
      'select * by id'          => 'SELECT b.*, u.acronym as owner FROM Book AS b INNER JOIN User as u ON b.idUser=u.id WHERE b.id=? AND deleted IS NULL;',
      'select * by key'           => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.key=? AND deleted IS NULL;',
      'select *'                => 'SELECT b.*, u.acronym as owner FROM Book AS b INNER JOIN User as u ON b.idUser=u.id WHERE deleted IS NULL;',
      'select all chapters'     => 'SELECT ch.*, c.title FROM Chapter as ch INNER JOIN Book AS b ON ch.idBook=b.id INNER JOIN Content as c ON ch.idContent=c.id ORDER BY position ASC;',
     );
    if(!isset($queries[$key])) {
      throw new Exception(t('No such SQL query.'));
    }
    return $queries[$key];
  }


  /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) { require_once(__DIR__.'/CMBookModule.php'); $m = new CMBookModule(); return $m->Manage($action); }

 
  /**
   * Save book, ff it has a id, use it to update current entry or else insert new entry.
   *
   * @returns boolean true if success else false.
   */
  public function Save() {
    $msg = null;
    if($this['id']) {
      $this->db->ExecuteQuery(self::SQL('update book'), array($this['key'], $this['title'], $this['id']));
      $msg = 'update';
    } else {
      $this->db->ExecuteQuery(self::SQL('insert book'), array($this['key'], $this['title'], $this->user['id']));
      $this['id'] = $this->db->LastInsertId();
      $msg = 'create';
    }
    $rowcount = $this->db->RowCount();
    if($rowcount) {
      $this->AddMessage('success', t('Success to !action book.', array('!action'=>$msg)));
    } else {
      $this->AddMessage('error', t('Failed to !action book.', array('!action'=>$msg)));
    }
    return $rowcount === 1;
  }
    

  /**
   * Delete book, set its deletion-date to enable wastebasket functionality.
   *
   * @returns boolean true if success else false.
   */
  public function Delete() {
    if($this['id']) {
      $this->db->ExecuteQuery(self::SQL('update content as deleted'), array($this['id']));
    }
    $rowcount = $this->db->RowCount();
    if($rowcount) {
      $this->AddMessage('success', t('Successfully deleted book.'));
    } else {
      $this->AddMessage('error', t('Failed to delete book'));
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
      $this->AddMessage('error', t('Failed to load book.'));
      return false;
    } else {
      $this->data = $res[0];
    }
    return true;
  }
  
  
  /**
   * List all details on book, build custom SQL-query as specified in options-array.
   *
   * @param array $options with various settings for the request.
   * @returns array with listing or null if empty.
   */
  public function GetEntries($options=array()) {
    $default = array(
      'type' => null,
      'order_by' => null,
      'order_order' => null,
      'limit' => 7,
    );
    $options = array_merge($default, $options);
    $sqlArgs = array();
    
    $type = empty($options['type']) ? null : " AND type = ?";
    if($type) $sqlArgs[] = $options['type'];
    
    $limit = empty($options['limit']) ? null : " LIMIT ?";
    if($limit) $sqlArgs[] = $options['limit'];
    
    $order_by = empty($options['order_by']) ? null : " ORDER BY {$options['order_by']}";
    
    $order_order = null;
    if(isset($options['order_order'])) {
      if(in_array(strtolower($options['order_order']), array('asc', 'desc'))) {
        $order_order = " {$options['order_order']}";
      } else {
        throw new Exception(t('Not valid group by order.'));
      }
    }
    
    $sql = "SELECT b.*, u.acronym as owner FROM Book AS b INNER JOIN User as u ON b.idUser=u.id WHERE deleted IS NULL".$type.$order_by.$order_order.$limit;
    return $this->db->ExecuteSelectQueryAndFetchAll($sql, $sqlArgs);
  }
  

  /**
   * Load all chapters.
   */
  public function LoadAllChapters() {
    $chapters = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select all chapters'));
    foreach($chapters as $key => $val) {
      $this->data['chapters'][$val['id']] = $chapters[$key];
    }
  }
  
  
  /**
   * Set current chapter to a valid or empty chapter.
   *
   * @param integer $id of the chapter or null to set as empty chapter.
   * @returns boolean true if succedded or false if failed.
   */
  public function SetCurrentChapter($id=null) {
    if(!isset($id)) {
      $this['chapter'] = new BookChapter();
      return true;
    }

    if(!isset($this['chapters'][$id])) {
      $this->AddMessage('error', t('No such chapter id.'));
      return false;
    }
    
    $this['chapter'] = new BookChapter;
    $this['chapter']->id = $id;
    $this['chapter']->position = $this['chapters'][$id]['position'];  
    $this['chapter']->idContent = $this['chapters'][$id]['idContent'];
    $this['chapter']->title = $this['chapters'][$id]['title'];
    return true;
  }


  /**
   * Save or update current chapter to database.
   *
   * @returns boolean true if succedded or false if failed.
   */
  public function SaveCurrentChapter() {
    $msg = null;
    if($this['chapter']->id) {
      $this->db->ExecuteQuery(self::SQL('update chapter'), array($this['chapter']->position, $this['chapter']->idContent, $this['chapter']->id));
      $msg = 'update';
    } else {
      $this->db->ExecuteQuery(self::SQL('insert chapter'), array($this['chapter']->position, $this['id'], $this['chapter']->idContent));
      $this['chapter']->id = $this->db->LastInsertId();
      $msg = 'create';
    }
    $rowcount = $this->db->RowCount();
    if($rowcount) {
      $this->AddMessage('success', t('Success to !action chapter.', array('!action'=>$msg)));
    } else {
      $this->AddMessage('error', t('Failed to !action chapter.', array('!action'=>$msg)));
    }
    return $rowcount === 1;
  }


}


/**
 * A class to manage book chapters.
 */
class BookChapter {

  /**
   * Properties
   */
  public $id = null;
  public $position = null;
  public $idContent = null;
  public $title = null;
}
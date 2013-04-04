<?php
/**
 * A model for content stored in database.
 * 
 * @package LydiaCore
 */
class CMContent extends CObject implements IHasSQL, ArrayAccess, IModule, Iterator {

  /**
   * Properties
   */
  public $data;
  public $set;
  public $position;


  /**
   * Constructor
   */
  public function __construct($id=null) {
    parent::__construct();
    if($id) {
      $this->LoadById($id);
    } else {
      $this->data = array();
      $this->set = $this->data;
      $this->position = 0;
    }
  }


  /**
   * Clone
   */
  /*public function __clone() {
    $this->data = unserialize(serialize($this->data));
    $this->set  = unserialize(serialize($this->set));
  }*/


  /**
   * Implementing ArrayAccess for $this->data
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->data[$offset]); }
  public function offsetUnset($offset) { unset($this->data[$offset]); }
  public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }


  /**
   * Implementing Iterator for $this->data and $this->set
   */
  function rewind() { $this->position = 0; }
  function current() { 
    $this->data = $this->set[$this->position];
    $this->Prepare();
    return $this;
  }
  function key() { return $this->position; }
  function next() { ++$this->position; }
  function valid() { return isset($this->set[$this->position]); }
  


  /**
   * Count number of items.
   */
  public function Count() {
    return count($this->set);
  }



  /**
   * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
   *
   * @param $key string the string that is the key of the wanted SQL-entry in the array.
   * @param $args array with arguments to make the SQL queri more flexible.
   * @return string.
   */
  public static function SQL($key=null, $args=null) {
    $order_order  = isset($args['order-order']) ? $args['order-order'] : 'ASC';
    $order_by     = isset($args['order-by'])    ? $args['order-by'] : 'id';    
    $queries = array(
      'table name content'        => "Content",
      'table name category'       => "Category",
      'drop table docs'           => "DROP TABLE IF EXISTS Docs;",
      'drop table content'        => "DROP TABLE IF EXISTS Content;",
      'drop table category'       => "DROP TABLE IF EXISTS Category;",
      'create table docs'         => "CREATE VIRTUAL TABLE Docs USING fts4(key, title, data);",
      'create table content'      => "CREATE TABLE IF NOT EXISTS Content (id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, idCategory INT default null, title TEXT, data TEXT, datafile TEXT default NULL, filter TEXT, url TEXT KEY, breadcrumb TEXT, parenttitle TEXT, template TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, published DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id), FOREIGN KEY(idCategory) REFERENCES Category(id));",
      'create table category'     => "CREATE TABLE IF NOT EXISTS Category (id INTEGER PRIMARY KEY, key TEXT KEY, title TEXT, description TEXT);",
      'export table content'      => 'SELECT * FROM Content;',
      'export table category'     => 'SELECT * FROM Category;',
      'optimize docs'             => "INSERT INTO Docs(Docs) VALUES('optimize');",
      'insert docs'               => "INSERT INTO Docs(rowid, key, title, data) VALUES(?,?,?,?);",
      'select id to index'        => "SELECT id FROM Content WHERE type NOT IN ('block') AND type IS NOT NULL AND deleted IS NULL;",
      //'schema create table'       => "SELECT sql FROM sqlite_master WHERE tbl_name = 'Content' AND type = 'table';",
      'insert content'            => 'INSERT INTO Content (key,type,idCategory,title,data,datafile,filter,url,breadcrumb,parenttitle,template,published,idUser) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);',
      'insert category'           => 'INSERT INTO Category (key,title) VALUES (?,?);',
      'select * by id'            => "SELECT c.*, u.id as uid, u.acronym as owner, u.name as owner_name, ca.title as category_title, ca.key as category_key FROM Content AS c INNER JOIN User as u ON c.idUser=u.id LEFT OUTER JOIN Category as ca ON ca.id=c.idCategory WHERE c.id=? AND c.deleted IS NULL;",
      'select * by key'           => "SELECT c.*, u.id as uid, u.acronym as owner, u.name as owner_name, ca.title as category_title, ca.key as category_key FROM Content AS c INNER JOIN User as u ON c.idUser=u.id LEFT OUTER JOIN Category as ca ON ca.id=c.idCategory WHERE c.key=? AND c.deleted IS NULL;",
      'select * by type'          => "SELECT c.*, u.id as uid, u.acronym as owner, u.name as owner_name, ca.title as category_title, ca.key as category_key FROM Content AS c INNER JOIN User as u ON c.idUser=u.id LEFT OUTER JOIN Category as ca ON ca.id=c.idCategory WHERE c.type=? AND c.deleted IS NULL ORDER BY {$order_by} {$order_order};",
      'select * by url'           => "SELECT c.*, u.id as uid, u.acronym as owner, u.name as owner_name, ca.title as category_title, ca.key as category_key FROM Content AS c INNER JOIN User as u ON c.idUser=u.id LEFT OUTER JOIN Category as ca ON ca.id=c.idCategory WHERE c.url=? AND c.deleted IS NULL;",
      'select id by url'          => "SELECT c.id FROM Content AS c WHERE url=? AND c.deleted IS NULL;",
      'select parents by url'     => "SELECT c.id, c.title, c.url, c.breadcrumb, c.parenttitle FROM Content AS c WHERE url IN (?) AND c.deleted IS NULL;",
      'select *'                  => "SELECT c.*, u.id as uid, u.acronym as owner, u.name as owner_name, ca.title as category_title, ca.key as category_key FROM Content AS c INNER JOIN User as u ON c.idUser=u.id LEFT OUTER JOIN Category as ca ON ca.id=c.idCategory WHERE c.deleted IS NULL;",
      'select categories by type' => "SELECT ca.*, count(ca.id) as items FROM Content as c LEFT OUTER JOIN Category as ca ON ca.id=c.idCategory WHERE c.type=? AND c.deleted IS NULL GROUP BY ca.title;",
      'select category by key'    => "SELECT ca.* FROM Category as ca WHERE ca.key=?;",
      'flexible select *'         => "SELECT c.*, u.id as uid, u.acronym as owner, u.name as owner_name, ca.title as category_title, ca.key as category_key FROM Content AS c INNER JOIN User as u ON c.idUser=u.id LEFT OUTER JOIN Category as ca ON ca.id=c.idCategory WHERE c.deleted IS NULL",
      'flexible match by type'    => "SELECT c.*, snippet(Docs, '<b>', '</b>', '…', -1, 48) as snippet FROM Docs AS d INNER JOIN Content AS c ON d.rowid=c.id WHERE Docs MATCH ? AND c.type=? ORDER BY c.updated DESC LIMIT ? OFFSET ?;",
      'count match by type'       => "SELECT COUNT(d.rowid) as hits FROM Docs AS d INNER JOIN Content AS c ON d.rowid=c.id WHERE Docs MATCH ? AND c.type=?;",
      'count flexible select'     => "SELECT COUNT(c.id) as hits FROM Content AS c INNER JOIN User as u ON c.idUser=u.id LEFT OUTER JOIN Category as ca ON ca.id=c.idCategory WHERE c.deleted IS NULL",
      'update docs'               => "UPDATE Docs SET key=?, title=?, data=? WHERE rowid=?;",
      'update content'            => "UPDATE Content SET key=?, type=?, idCategory=?, title=?, data=?, datafile=?, filter=?, url=?, breadcrumb=?, parenttitle=?, template=?, published=?, updated=datetime('now') WHERE id=?;",
      'update content as deleted' => "UPDATE Content SET deleted=datetime('now') WHERE id=?;",
      'delete docs'               => "DELETE FROM Docs WHERE rowid=?;",
    );

    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }

    return $queries[$key];
  }



  /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) { require_once(__DIR__.'/CMContentModule.php'); $m = new CMContentModule(); return $m->Manage($action); }

 

  /**
   * Save content. If it has a id, use it to update current entry or else insert new entry.
   *
   * @return boolean true if success else false.
   */
  public function Save() {
    $msg = null;

    if($this['id']) {
      $this->db->ExecuteQuery(self::SQL('update content'), array($this['key'], $this['type'], $this['idCategory'], $this['title'], $this['data'], $this['datafile'], $this['filter'], $this['url'], $this['breadcrumb'], $this['parenttitle'], $this['template'], $this['published'], $this['id']));
      $this->db->ExecuteQuery(self::SQL('update docs'), array($this['key'], $this['title'], $this->GetPureText(), $this['id']));
      $msg = 'update';
    } else {
      $this->db->ExecuteQuery(self::SQL('insert content'), array($this['key'], $this['type'], $this['idCategory'], $this['title'], $this['data'], $this['datafile'], $this['filter'], $this['url'], $this['breadcrumb'], $this['parenttitle'], $this['template'], $this['published'], $this->user['id']));
      $this['id'] = $this->db->LastInsertId();
      $this->db->ExecuteQuery(self::SQL('insert docs'), array($this['id'], $this['key'], $this['title'], $this->GetPureText()));
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
   * Delete content. Set its deletion-date to enable wastebasket functionality.
   *
   * @return boolean true if success else false.
   */
  public function Delete() {
    if($this['id']) {
      $this->db->ExecuteQuery(self::SQL('update content as deleted'), array($this['id']));
      $this->db->ExecuteQuery(self::SQL('delete docs'), array($this['id']));
    }
    $rowcount = $this->db->RowCount();
    if($rowcount) {
      $this->AddMessage('success', "Successfully set content '" . htmlEnt($this['key']) . "' as deleted.");
    } else {
      $this->AddMessage('error', "Failed to set content '" . htmlEnt($this['key']) . "' as deleted.");
    }
    return $rowcount === 1;
  }
    


  /**
   * Load content by id.
   *
   * @param $id integer the id of the content.
   * @return boolean/Class $this if success else false.
   */
  public function LoadById($id) {
    
    $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by id'), array($id));
    
    if(empty($res)) {
      $this->AddMessage('error', "Failed to load content by id.");
      return false;
    } 
    
    $this->data = $res[0];
    $this->set = $this->data;
    $this->position = 0;
    return $this;
  }
  
  

  /**
   * Load content by key.
   *
   * @param string $key the key of the content.
   * @return boolean/Class $this if success else false.
   */
  public function LoadByKey($key=null) {
    
    if(!$key) return false;
    
    $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by key'), array($key));
    
    if(empty($res)) {
      $this->AddMessage('error', "Failed to load content by key.");
      return false;
    } 
    
    $this->data = $res[0];
    $this->set = $this->data;
    $this->position = 0;
    return $this;
  }
  
  

  /**
   * Check if url is associated with some content.
   *
   * @param string $url the url associated with the content.
   * @return boolean true if success else false.
   */
  public function ContentHasUrl($url) {
    $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select id by url'), array($url));
    return empty($res) ? false : true;
  }
  


  /**
   * Load content by url.
   *
   * @param string $url the url associated with the content.
   * @param boolean $displayError display error message on failure or not.
   * @return boolean/Class $this if success else false.
   */
  public function LoadByUrl($url, $displayError=true) {

    $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by url'), array($url));
    
    if(empty($res)) {

      if($displayError) {
        $this->AddMessage('error', "Failed to load content by url.");
      }

      return false;
    } 
    
    $this->data = $res[0];
    $this->set = $this->data;
    $this->position = 0;
    return $this;
  }
  


  /**
   * Load content parents (by content->url) of current content if it exists. Userful for creating breadcrumbs and parts of page title.
   *
   * @param string $url the url associated with the content.
   * @return boolean/Class $this if success else false.
   */
  public function LoadParents() {

    if(empty($this['url'])) {
      return false;
    }

    $parts = explode('/', $this['url']);
    array_pop($parts);
    $base = null;
    $urls = array();
    foreach($parts as $val) {
      $urls[] = $base .= "{$val}";
      $base .= '/'; 
    }
    $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select parents by url'), array($urls));
    
    if(empty($res)) {
      return false;
    } 
    
    $this['parents'] = $res;
    return $this;
  }



  /**
   * Create an array suitable for creating an breadcrumb.
   *
   * @return array with values for the breadcrumb.
   */
  public function CreateBreadcrumbFromParents() {

    $breadcrumbs = array();
    if(!empty($this['parents'])) {
      foreach($this['parents'] as $val) {
        $label  = empty($val['breadcrumb'])  ? $val['title'] : $val['breadcrumb'] ;
        $breadcrumbs[] = array('label' => $label, 'url' => $val['url']);
      }
    }

    $label  = empty($this['breadcrumb'])  ? $this['title'] : $this['breadcrumb'] ;
    $url    = empty($this['url'])         ? $this->request->request : $this['url'];
    $breadcrumbs[] = array('label' => $label, 'url' => $url);

    uasort($breadcrumbs, function($a, $b) {
      return strcmp($a['url'], $b['url']);
    });

    return $breadcrumbs;
  }



  /**
   * Adding parents titles to content title.
   *
   * @param string $title the title of the page.
   * @return string the resulting title.
   */
  public function AddParentsTitle($title) {

    if(empty($this['parents'])) {
      return $title;
    }

    $sep = $this->config['title_separator'];
    $parents = null;
    foreach($this['parents'] as $val) {
      $parent = empty($val['parenttitle']) ? $val['title'] : $val['parenttitle'];
      $parents = $sep . $parent;
    }
    
    return $title . $parents;
  }



  /**
   * Get the page title, use parenttitle if set, else use title. 
   *
   * @return string the resulting title.
   */
  public function GetPageTitle() {
    return empty($this['parenttitle']) ? $this['title'] : $this['parenttitle'];
  }



  /**
   * List all content.
   *
   * @param $args array with various settings for the request. Default is null.
   * @return array with listing or null if empty.
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
  


  /**
   * List all content as specified in array, build custom SQL-query.
   *
   * @param array $options with various settings for the request.
   * @return $this.
   */
  public function GetEntries($options=array()) {
    $default = array(
      'type' => null,
      'category_key' => null,
      'order_by' => null,
      'order_order' => null,
      'limit' => 7,
      'offset' => 0,
    );
    $options = array_merge($default, $options);
    $args = $argsc = array();
    
    $type = empty($options['type']) ? null : " AND type = ?";
    if($type) {
      $args[] = $argsc[] = $options['type'];
    } 
    
    if(isset($options['category_key'])) {
      $catKey = " AND ca.key = ?";
      $args[] = $argsc[] = $options['category_key'];
    }

    $limit = empty($options['limit']) ? null : " LIMIT ?";
    if($limit) {
      $args[] = $options['limit'];
    }
    
    $offset = empty($options['offset']) ? null : " OFFSET ?";
    if($offset) {
      $args[] = $options['offset'];
    }
    
    $order_by = empty($options['order_by']) ? null : " ORDER BY {$options['order_by']}";
    
    $order_order = null;
    if(isset($options['order_order'])) {
      if(in_array(strtolower($options['order_order']), array('asc', 'desc'))) {
        $order_order = " {$options['order_order']}";
      } else {
        throw new Exception(t('Not valid group by order.'));
      }
    }

    $this->position = 0;
    $res          = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('count flexible select').$type.$catKey, $argsc);
    $this->hits   = $res[0]['hits'];
    $this->set = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('flexible select *').$type.$catKey.$order_by.$order_order.$limit.$offset, $args);
    $this->data = $this->set[$this->position];
    return $this;
  }
  


  /**
   * Search content.
   *
   * @param array $options with various settings for the request.
   * @return $this.
   */
  public function SearchEntries($options=array()) {
    $default = array(
      'order_by'    => 'updated',
      'order_order' => 'ASC',
      'type'    => null,
      'limit'   => 10,
      'offset'  => 0,
      'match'   => null,
    );
    $options = array_merge($default, $options);
    $args = $args1 = array();

    // This is a fulltext search
    if(empty($options['match'])) {
      $this->position = 0;
      $this->hits = 0;
      $this->set = null;
      $this->data = null;
      return $this;
    }

    $args[] = $args1[] = $options['match'];
    $args[] = $args1[] = $options['type'];    
    //$args[] = $options['order_by'];
    //$args[] = $options['order_order'];
    $args[] = $options['limit'];
    $args[] = $options['offset'];

    $this->position = 0;
    $res          = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('count match by type'), $args1);
    $this->hits   = $res[0]['hits'];
    $this->set    = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('flexible match by type'), $args);
    $this->data   = $this->set[$this->position];
    return $this;
  }
  


  /**
   * Get category.
   *
   * @param array $options with various settings for the request.
   * @return array with listing or null if empty.
   */
  public function GetCategory($key=null) {
    if(!$key) return null;
    $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select category by key'), array($key));
    return $res[0];
  }
  

  /**
   * Get categories.
   *
   * @param array $options with various settings for the request.
   * @return array with listing or null if empty.
   */
  public function GetCategories($options=array()) {
    $default = array(
      'type' => null,
    );
    $options = array_merge($default, $options);    
    return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select categories by type'), array($options['type']));
  }
  

  /**
   * Get a list of supported textfilters.
   *
   * @return array with list of supported filters.
   */
  public static function SupportedFilters() {
    return array(
      'plain' => t('Convert http://webb.com/ to clickable links. Convert newline to <br />.'),
      'bbcode' => t('Support bbcode. Convert newline to <br />.'),
      'htmlpurify' => t('Treat data as HTML and use HTMLPurify to filter content. Convert newline to <br />.'),
      'markdown' => t('Support Markdown-syntax together with Typographer (SmartyPants).'),
      'markdownx' => t('Support extended Markdown-syntax together with Typographer (SmartyPants). Converts links, shorttags'),
    );
  }
  

  
  /**
   * Filter content according to a filter.
   *
   * @param $data string of text to filter and format according its filter settings.
   * @return string with the filtered data.
   */
  public static function Filter($data, $filter) {
    switch($filter) {
      /*case 'php': $data = nl2br(makeClickable(eval('?>'.$data))); break;
      case 'html': $data = nl2br(makeClickable($data)); break;*/
      case 'markdowny':   $data = CTextFilter::Typographer(CTextFilter::MarkdownExtra(CTextFilter::ShortTags($data))); break;
      case 'markdownx':   $data = CTextFilter::MakeClickable(CTextFilter::Typographer(CTextFilter::MarkdownExtra(CTextFilter::ShortTags($data)))); break;
      case 'markdown':    $data = CTextFilter::Typographer(CTextFilter::Markdown($data)); break;
      case 'htmlpurify':  $data = nl2br(CTextFilter::Purify($data)); break;
      case 'bbcode':      $data = nl2br(CTextFilter::Bbcode2HTML(htmlEnt($data))); break;
      case 'plain': 
      default: $data = nl2br(CTextFilter::MakeClickable(htmlEnt($data))); break;
    }
    return $data;
  }
  
  

  /**
   * Get the filtered content.
   *
   * @return string with the filtered data.
   */
  public function GetFilteredData() {
    $data = null;
    $dir = CMModules::GetModuleDirectory(get_class(), 'txt');
    if($this['datafile']) {
      //$data = "\n".file_get_contents(LYDIA_DATA_PATH.'/'.strtolower(get_class()).'/txt/'.$this['datafile']);
      $data = "\n".file_get_contents("{$dir}/{$this['datafile']}");
    }
    $this->data['data_filtered'] = $this->Filter($this['data'] . $data, $this['filter']);
    $pos = stripos($this->data['data_filtered'], '<!--more-->');
    $this->data['data_has_more'] = $pos;
    if($pos) {
      $this->data['data_short_filtered'] = substr($this->data['data_filtered'], 0, $pos);
    } else {
      $this->data['data_short_filtered'] = $this->data['data_filtered'];
    }
    return $this->data['data_filtered'];
  }
  
  

  /**
   * Get content as pure text.
   *
   * @return string with the pure text.
   */
  public function GetPureText() {
    return preg_replace('/\s+/', ' ', strip_tags($this->GetFilteredData()));
  }
  
  

  /**
   * Get the TOC of headings to a certain level.
   *
   * @param integer $level which level of headings to use for toc.
   * @return array with entries to generate a TOC.
   */
  public function GetTableOfContent($level=4) {
  	$pattern = '/<(h[2-'.$level.'])([^>]*)>(.*)<\/h[2-'.$level.']>/';
  	preg_match_all($pattern, $this->data['data_filtered'], $matches, PREG_SET_ORDER);
    $this->data['toc'] = array();
    $this->data['toc_formatted'] = null;
    foreach($matches as $val) {
      preg_match('/id=[\'"]([^>"\']+)/', $val[2], $id);
      $id = isset($id[1]) ? $id[1] : null;
      $this->data['toc'][] = array('level' => (isset($matches[1]) ? $matches[1] : null), 'id' => $id, 'label' => (isset($val[3]) ? $val[3] : null));
      $a1 = $id ? "<a href='#{$id}'>" : null;
      $a2 = $id ? "</a>" : null;
      //$this->data['toc_formatted'] .= "<li class='{$val[1]}'>{$a1}" . htmlEnt($val[3]) . "{$a2}</li>\n";
      $this->data['toc_formatted'] .= "<li class='{$val[1]}'>{$a1}{$val[3]}{$a2}</li>\n";
    }
    if($this->data['toc_formatted']) {
      $this->data['toc_formatted'] = "<ul>\n" . $this->data['toc_formatted'] . "</ul>\n";
    }
    return $this->data['toc'];
  }
  
  
  /**
   * Prepare all data before sending to view, stores all prepared data in object, easy for views to access.
   * 
   * @return $this.
   */
  public function Prepare() {
    $this->GetFilteredData();
    $this->GetTableOfContent();
    return $this;
  }
  
  
  /**
   * Returns the excerpt of the text with at most the specified amount of characters.
   * 
   * @param int $chars the number of characters to return.
   * @param boolean $hard do a hard break at exactly $chars characters or find closest space.
   * @return string as the excerpt.
   */
  public function GetExcerpt($chars=139, $hard=false) {
    if(!isset($this->data['data_filtered'])) {
      return null;
    }
    $excerpt = strip_tags($this->data['data_filtered']);

    if(strlen($excerpt) > $chars) {
      $excerpt   = substr($excerpt, 0, $chars-1);
    }

    if(!$hard) {
      $lastSpace = strrpos($excerpt, ' ');
      $excerpt   = substr($excerpt, 0, $lastSpace);
    }

    return $excerpt;
  }
  
  
  /**
   * Returns the first paragraph ot the text.
   * 
   * @return string as the first paragraph.
   */
  public function GetFirstParagraph() {
    if(!isset($this->data['data_filtered'])) {
      return null;
    }
    $excerpt = $this->data['data_filtered'];

    $firstPara = strpos($excerpt, '</p>');
    $excerpt   = substr($excerpt, 0, $firstPara + 4);

    return $excerpt;
  }
  
  
  /**
   * Check if current user own this content.
   *
   * @return boolean true if current user is owner of content, else false.
   */
  public function CurrentUserIsOwner() {
    return $this->user[id] === $this['uid'];
  }



  /**
   * Get time when the content was last updated.
   *
   * @return string with the time.
   */
  public function PublishTime() {
    if(!empty($this['published'])) {
      return $this['published'];
    } else if(isset($this['updated'])) {
      return $this['updated'];
    } else {
      return $this['created'];
    } 
  }



  /**
   * Get the action for latest updated of the content.
   *
   * @return string with the time.
   */
  public function PublishAction() {
    if(!empty($this['published'])) {
      //return t('Published');
      return t('Last updated');
    } else if(isset($this['updated'])) {
      return t('Updated');
    } else {
      return t('Created');
    } 
  }



}

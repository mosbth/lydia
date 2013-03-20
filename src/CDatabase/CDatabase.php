<?php
/**
 * Database wrapper, provides a database API for the framework but hides details of implementation.
 *
 * @package LydiaCore
 */
class CDatabase {

	/**
	 * Members
	 */
  private $dsn;
  private $driver;
  private $db = null;
  private $stmt = null;
  private static $numQueries = 0;
  private static $queries = array();


  /**
   * Constructor
   */
  public function __construct($dsn, $username = null, $password = null, $driver_options = null) {
    $this->db = new PDO($dsn, $username, $password, $driver_options);
    $this->db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $this->dsn = $dsn;
    $this->driver = strtok($dsn, ':');
  }
  
  
  /**
   * Set an attribute on the database
   */
  public function SetAttribute($attribute, $value) {
    return $this->db->setAttribute($attribute, $value);
  }


  /**
   * Getters
   */
  public function GetNumQueries() { return self::$numQueries; }
  public function GetQueries() { return self::$queries; }


  /**
   * Get meta information of the table.
   *
   * @return array with meta information for the latest statement.
   */
  public function GetColumnMeta(){
    return $this->stmt->GetColumnMeta();
  }


  /**
   * Create an external PHP function to use in SQL query.
   *
   * @param string $functionName name of the function.
   * @param callable $callback the function to call.
   * @param int $numArgs hint to the compiler if there is a predefined number of arguments.
   * @return boolean true if success else false.
   */
  public function CreateFunction($functionName, $callback, $numArgs=null) {
    if($this->driver != 'sqlite') {
      throw new Exception("Not supported to create database function with PDO extension: {$this->driver }"); 
    }
    return $this->db->sqliteCreateFunction ($functionName, $callback, $numArgs);
  }


  /**
   * Extend params array to support arrays in it.
   *
   * @param string $query as the query to prepare.
   * @param array $params the parameters that may contain arrays.
   * @return array with query and params.
   */
  protected function ExpandParamArray($query, $params) {
    $param = array();
    $offset = -1;
    
    foreach($params as $val) {
    
      $offset = strpos($query, '?', $offset + 1);

      if(is_array($val)) {
        $nrOfItems = count($val);
        if($nrOfItems) {
          $query = substr($query, 0, $offset) . str_repeat('?,', $nrOfItems  - 1) . '?' . substr($query, $offset + 1);
          $param = array_merge($param, $val);
        } else {
          $param[] = null;
        }
      } else {
        $param[] = $val;
      }
    }

    return array($query, $param);
  }


  /**
   * Execute a select-query with arguments and return the resultset.
   * 
   * @param string $query the SQL query with ?.
   * @param array $params array which contains the argument to replace ?.
   * @param boolean $debug defaults to false, set to true to print out the sql query before executing it.
   * @return array with resultset.
   */
  public function ExecuteSelectQueryAndFetchAll($query, $params=array(), $debug=false) {
    list($query, $params) = $this->ExpandParamArray($query, $params);
    self::$queries[] = $query; 
    self::$numQueries++;
    if($debug) {
      $n = self::$numQueries;
      echo "<p>Query = <br/><pre>{$query}</pre></p><p>Num query = {$n}</p><p><pre>".print_r($params, 1)."</pre></p>";
    }
    $this->stmt = $this->db->prepare($query);
    $this->stmt->execute($params);
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  /**
   * Execute a SQL-query and ignore the resultset.
   */
  public function ExecuteQuery($query, $params = array()) {
    list($query, $params) = $this->ExpandParamArray($query, $params);
    $this->stmt = $this->db->prepare($query);
    self::$queries[] = $query; 
    self::$numQueries++;
    return $this->stmt->execute($params);
  }


  /**
   * Return last insert id.
   */
  public function LastInsertId() {
    return $this->db->lastInsertid();
  }


  /**
   * Return rows affected of last INSERT, UPDATE, DELETE
   */
  public function RowCount() {
    return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();
  }


}

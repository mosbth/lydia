<?php
/**
 * A model for managing Lydia modules.
 * 
 * @package LydiaCore
 */
class CMModules extends CObject {

  /**
   * Properties
   */
  private $lydiaCoreModules = array('CLydia', 'CDatabase', 'CRequest', 'CViewContainer', 'CSession', 'CObject');
  private $lydiaCMFModules = array('CForm', 'CCPage', 'CMUser', 'CCUser', 'CMContent', 'CCContent', 'CFormUserLogin', 
                                   'CFormUserProfile', 'CFormUserCreate', 'CFormContent', 'CHTMLPurifier', 'CRSSFeed',
                                   'CMRSSAggregator',);
  private $lydiaAppModules = array('CCBlog', 'CCAllSeeingEye');

  private $supportedActions = array(
    'install' => 'Do a fresh install, all data is removed and essentials is re-created.', 
    'sample' => 'Insert some sample data to make it look like sample website.',
    'crontab' => 'Perform crontab actions on regulare basis.',
    'rebuild-index' => 'Rebuild the searchable index for each module.',
    'prune-cache' => 'Remove all cached objects.',
//    'backup' => 'Do a backup of this installation.',
//    'export' => 'Export a zip-file with this installation.',
//    'import' => 'Upload a zip-file and use it as base for this installation, remove all other content.',
    'export-db' => 'Export the database as SQL-commands in a text-file.',
    'supported-actions' => 'List all supported actions.', 
  );


  /**
   * Constructor
   * 
   * @param string $secret a key to enable remote management without being logged in. 
   */
  public function __construct($secret=null) { 
    parent::__construct();
    $cf = new CInterceptionFilter();
    if($secret) {
      $cf->MatchSecretKey($secret);
    } else {
      $cf->AuthenticatedOrLogin()->AdminOrForbidden();
    }
  }


  /**
   * A list of all available controllers/methods
   *
   * @returns array list of controllers (key) and an array of methods
   */
  public function AvailableControllers() {	
    $controllers = array();
    foreach($this->config['controllers'] as $key => $val) {
      if($val['enabled']) {
        $rc = new ReflectionClass($val['class']);
        $controllers[$key] = array();
        $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach($methods as $method) {
          if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
            $methodName = mb_strtolower($method->name);
            $controllers[$key][] = $methodName;
          }
        }
        sort($controllers[$key], SORT_LOCALE_STRING);
      }
    }
    ksort($controllers, SORT_LOCALE_STRING);
    return $controllers;
  }


  /**
   * Read and analyse all modules.
   *
   * @returns array with a entry for each module with the module name as the key. 
   *                Returns boolean false if $src can not be opened.
   */
  public function ReadAndAnalyse() {
    $src = LYDIA_INSTALL_PATH.'/src';
    if(!$dir = dir($src)) throw new Exception(t('Could not open the directory.'));
    $modules = array();
    while (($module = $dir->read()) !== false) {
      if(is_dir("$src/$module") && class_exists($module)) {
        $modules[$module] = $this->GetDetailsOfModule($module);
        $modules[$module]['isSiteDefined'] = false;
      }
    }
    $dir->close();
    
    $src = LYDIA_SITE_PATH.'/src';
    if(!$dir = dir($src)) throw new Exception(t('Could not open the directory.'));
    while (($module = $dir->read()) !== false) {
      if(is_dir("$src/$module") && class_exists($module)) {
        $modules[$module] = $this->GetDetailsOfModule($module);
        $modules[$module]['isSiteDefined'] = true;
      }
    }
    $dir->close();
    ksort($modules, SORT_LOCALE_STRING);
    return $modules;
  }
  

  /**
   * Get info and details about a module.
   *
   * @param $module string with the module name.
   * @returns array with information on the module.
   */
  private function GetDetailsOfModule($module) {
    $details = array();
    if(class_exists($module)) {
      $rc = new ReflectionClass($module);
      $details['name']          = $rc->name;
      $details['filename']      = $rc->getFileName();
      $details['doccomment']    = $rc->getDocComment();
      $details['interface']     = $rc->getInterfaceNames();
      $details['isController']  = $rc->implementsInterface('IController');
      $details['isModel']       = preg_match('/^CM[A-Z]/', $rc->name);
      $details['hasSQL']        = $rc->implementsInterface('IHasSQL');
      $details['isManageable']  = $rc->implementsInterface('IModule');
      $details['isLydiaCore']   = in_array($rc->name, $this->lydiaCoreModules);
      $details['isLydiaCMF']    = in_array($rc->name, $this->lydiaCMFModules);
      $details['isLydiaApp']    = in_array($rc->name, $this->lydiaAppModules);
      $details['publicMethods']     = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
      $details['protectedMethods']  = $rc->getMethods(ReflectionMethod::IS_PROTECTED);
      $details['privateMethods']    = $rc->getMethods(ReflectionMethod::IS_PRIVATE);
      $details['staticMethods']     = $rc->getMethods(ReflectionMethod::IS_STATIC);
    }
    return $details;
  }
  

  /**
   * Get info and details about the methods of a module.
   *
   * @param $module string with the module name.
   * @returns array with information on the methods.
   */
  private function GetDetailsOfModuleMethods($module) {
    $methods = array();
    if(class_exists($module)) {
      $rc = new ReflectionClass($module);
      $classMethods = $rc->getMethods();
      foreach($classMethods as $val) {
        $methodName = $val->name;
        $rm = $rc->GetMethod($methodName);
        $methods[$methodName]['name']          = $rm->getName();
        $methods[$methodName]['doccomment']    = $rm->getDocComment();
        $methods[$methodName]['startline']     = $rm->getStartLine();
        $methods[$methodName]['endline']       = $rm->getEndLine();
        $methods[$methodName]['isPublic']      = $rm->isPublic();
        $methods[$methodName]['isProtected']   = $rm->isProtected();
        $methods[$methodName]['isPrivate']     = $rm->isPrivate();
        $methods[$methodName]['isStatic']      = $rm->isStatic();
      }
    }
    ksort($methods, SORT_LOCALE_STRING);
    return $methods;
  }
  

  /**
   * Get info and details about a module.
   *
   * @param $module string with the module name.
   * @returns array with information on the module.
   */
  public function ReadAndAnalyseModule($module) {
    $details = $this->GetDetailsOfModule($module);
    $details['methods'] = $this->GetDetailsOfModuleMethods($module);
    return $details;
  }
  

  /**
   * Invoke an action to each manageable module.
   *
   * @param string $action the action to invoke.
   * manage. If null it will read all entries from ReadAndAnalyse().
   * @returns array with a entry for each module and the result from installing it.
   */
  public function InvokeActionToManage($action) {
    if(!array_key_exists($action, $this->supportedActions)) { throw new Exception(t('Not a valid action.')); }
    $modules = $this->ReadAndAnalyse();
    uksort($modules, function($a, $b) { return ($a == 'CMUser' ? -1 : ($b == 'CMUser' ? 1 : 0)); } );
    $modules = array_merge(array('CMUser'=>null,'CMContent'=>null), $modules);

    $result = array();
    foreach($modules as $module) {
      if($module['isManageable']) {
        $classname = $module['name'];
        $rc = new ReflectionClass($classname);
        $obj = $rc->newInstance();
        $method = $rc->getMethod('Manage');
        $result[$classname]['name']    = $classname;
        $result[$classname]['result']  = $method->invoke($obj, $action);
        $result[$classname]['output']  = isset($result[$classname]['result'][2]) ? $result[$classname]['result'][2] : null;        
      }
    }
    return $result;
  }


  /**
   * Get supported actions.
   *
   * @returns array with list of strings of supported actions.
   */
  public function GetSupportedActions() {
    return $this->supportedActions;
  }


  /**
   * Dump SQL from table data.
   *
   * @param string $tableName, the name of the table.
   * @param string $sqlExport, sql to do SELECT * FROM $tableName.
   * @param string $sqlCreate, sql to create the table.
   * @param string $sqlDrop, sql to drop the table.
   * @returns string with SQL commands.
   */
  public function DumpTableToSQL($tableName, $sqlExport, $sqlCreate, $sqlDrop) {
    $res = $this->db->ExecuteSelectQueryAndFetchAll($sqlExport);
    $sql = "-- ## Start Table {$tableName}\n{$sqlDrop}\n{$sqlCreate}\n";
    $cols = isset($res[0]) ? implode(', ', array_keys($res[0])) : null;
  
    // Find column types
    $matches = array();
    preg_match_all('/\((.+)\)/', $sqlCreate, $matches);
    $matches = explode(',', $matches[1][0]);
    foreach($matches as $val) {
      $col = explode(' ', trim($val));
      $types[$col[0]] = isset($col[1]) ? $col[1] : null;
    }
  
    foreach($res as $val) {
      $values = null;
      foreach($val as $key => $value) {
        if(is_null($value)) {
          $values .= 'NULL, ';
        } elseif(preg_match('/TEXT|CHAR|VARCHAR|DATETIME/i', $types[$key])) {
          $values .= "'" . str_replace("'", "''", $value) . "', ";
        } else {
          $values .= "{$value}, ";
        }
      }
      $values = substr($values, 0, -2);
      $sql .= "INSERT INTO {$tableName} ({$cols}) VALUES(" . str_replace("\n", '\n', $values) . ");\n";
    }
    return $sql . "-- ## End Table {$tableName}\n";
  }
  
  
  /**
   * Perform SQL queries.
   *
   * @param string $sql, one query per row, comments start with --
   * @returns array with results, row by row.
   */
  public function DoSQL($sql) {
    $meta = array_fill_keys(array('total_rows', 'comments', 'drop', 'create', 'alter', 'insert', 'update', 'unknown', 'success', 'failed', 'rowcount'), 0);
    $rowcount = array_fill_keys(array('drop', 'create', 'alter', 'insert', 'update'), 0);
    $rows =  explode("\n", $sql);
    foreach($rows as $row) {
      if(preg_match('/^[\s]*$/', $row)) continue;
      $meta['total_rows']++;
      $row = trim($row);
      if(substr_compare('--', $row, 0, 2) == 0) { $meta['comments']++; continue;}
      if(preg_match('/^(CREATE|DROP|ALTER|INSERT|UPDATE)/i', $row, $command)) {
        $cmd = strtolower($command[0]);
        $meta[$cmd]++;
        try {
          $res = $this->db->ExecuteQuery(str_replace('\n', "\n", $row));
          $rc = $this->db->RowCount();
          $meta['rowcount'] += $rc;
          $rowcount[$cmd] += $rc;
          $meta['success']++;
        } catch(Exception $e) {
          $meta['failed']++;
          $meta['failed_query'] = mb_strcut($row, 0, 2000);
          break;
        }
      } else {
        $meta['unknown']++;
      }
    }
    foreach($rowcount as $key => $val) {
      $meta[$key] = "{$meta[$key]} " . t('(rows affected: !rows)', array('!rows'=>$val));
    }
    return array('meta'=>$meta);
  }
  


  /**
   * Get the name of the module directory.
   *
   * @param string $module, the classname of the module.
   * @param string $directory, a subdirectory which defaults to null.
   * @returns mixed null if directory exists, true if created false if failed.
   */
   public static function GetModuleDirectory($module, $directory=null) {
    return LYDIA_DATA_PATH.strtolower("/$module/$directory");
  }

  

  /**
   * Create a directory in site/data for a specific module, using only lowercase letters.
   *
   * @param string $module, the classname of the module.
   * @param string $directory, a subdirectory which defaults to null.
   * @returns mixed null if directory exists, true if created false if failed.
   */
   public static function CreateModuleDirectory($module, $directory=null) {
    $path = self::GetModuleDirectory($module, $directory);
    if(is_dir($path)) return null;
    if(mkdir($path, 0777, true)) return true;
    return false;
  }

  
}

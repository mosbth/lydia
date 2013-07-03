<?php
/**
 * A class for caching objects.
 * 
 * @package LydiaCore
 */
class CCache implements IModule {


  /**
   * Properties
   */
  public $options;



  /**
   * Constructor. 
   *
   * @param $text string the dirty HTML.
   * @return string as the clean HTML.
   */
   public function __construct($options=array()) {
    $default = array(
      'age' => 7 * 24 * 60 * 60,
      'dir' => CMModules::GetModuleDirectory(__CLASS__),
    );
    $this->options = array_merge($default, $options);
  }
  


  /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) { require_once(__DIR__.'/CCacheModule.php'); $m = new CCacheModule(); return $m->Manage($action); }

  

  /**
   * Get an item from the cache it its there. 
   *
   * @param string $key to the cached object.
   * @return mixed the cached object or false if it has aged or null if it does not exists.
   */
   public function Get($key) {   
    $file = $this->options['dir'] . '/' . strtolower($key);
    
    if(is_file($file)) {
      if(filemtime($file) + $this->options['age'] > time()) {
        return unserialize(file_get_contents($file));
      }
      return false;
    }
    return null;
  }



   /**
   * Put an item to the cache. 
   *
   * @param string $key to the cached object.
   * @throws Exception if failing to write to cache.
   */
   public function Put($key, $item) {   
    $file = $this->options['dir'] . '/' . strtolower($key);
    if(!file_put_contents($file, serialize($item))) {
      throw new Exception("Failed writing cache object '{$key}'.");
    }
  }
  


 /**
   * Prune a item from cache. 
   *
   * @param string $key to the cached object.
   */
   public function Prune($key) {
    @unlink($this->options['dir'] . '/' . strtolower($key));
  }



 /**
   * Prune all items from cache. 
   *
   * @return int number of items removed.
   */
   public function PruneAll() {
    $files = glob($this->options['dir'].'/*');
    $items = count($files);
    array_map('unlink', $files);
    return $items;
  }


}

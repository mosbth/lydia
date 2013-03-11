<?php
/**
 * Create base class for creating custom blocks of contents which can be included as function views.
 * 
 * @package LydiaCore
 */
class CMBlock extends CObject implements IModule, ISingleton {

  /**
   * Members
   */
  private static $instance = null;



  /**s
   * Constructor
   */
  public function __construct() { parent::__construct(); }



  /**
   * Implementing interface IModule. Manage install/update/deinstall and equal actions.
   */
  public function Manage($action=null) { /*require_once(__DIR__.'/CMBlockModule.php');  $m = new CMBlockModule(); return $m->Manage($action); */}



  /**
   * Singleton pattern. Get the instance of the latest created object or create a new one. 
   * @return CMBlock The instance of this class.
   */
  public static function Instance() {
    return is_null(self::$instance) ? self::$instance = new static : self::$instance;
  }

 
  /**
   * Custom method for a template to change $this->page before adding as views.
   *
   */
 /* public static function TemplateKmom() {
      $this->page['regions'][]  = array(
        'region'  => 'sidebar', 
        'type'    => 'function', 
        'content' => function() { return CBlock::Instance()->OophpKmomListSidebar(); },
      );
  } */



} 
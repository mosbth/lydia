<?php
/**
 * Holding a instance of CLydia to enable use of $this in subclasses.
 *
 * @package LydiaCore
 */
class CObject {

	public $config;
	public $request;
	public $data;

	/**
	 * Constructor
	 */
	protected function __construct() {
    $ly = CLydia::Instance();
    $this->config   = &$ly->config;
    $this->request  = &$ly->request;
    $this->data     = &$ly->data;
  }

}
  

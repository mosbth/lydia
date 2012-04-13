<?php
/**
 * A blog controller to display a blog-like list of all content labelled as "post".
 * 
 * @package LydiaCore
 */
class CCBlog extends CObject implements IController {


  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }


  /**
   * Display all content of the type "post".
   */
  public function Index() {
    $content = new CMContent();
    $this->views->SetTitle('Blog')
                ->AddInclude(__DIR__ . '/index.tpl.php', array(
                  'contents' => $content->ListAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC')),
                ));
  }


} 
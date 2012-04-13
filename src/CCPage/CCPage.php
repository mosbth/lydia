<?php
/**
 * A page controller to display a page, for example an about-page, displays content labelled as "page".
 * 
 * @package LydiaCore
 */
class CCPage extends CObject implements IController {


  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }


  /**
   * Display an empty page.
   */
  public function Index() {
    $content = new CMContent();
    $this->views->SetTitle('Page')
                ->AddInclude(__DIR__ . '/index.tpl.php', array(
                  'content' => null,
                ));
  }


  /**
   * Display a page.
   *
   * @param $id integer the id of the page.
   */
  public function View($id=null) {
    $content = new CMContent($id);
    $this->views->SetTitle('Page: '.htmlEnt($content['title']))
                ->AddInclude(__DIR__ . '/index.tpl.php', array(
                  'content' => $content,
                ));
  }


} 
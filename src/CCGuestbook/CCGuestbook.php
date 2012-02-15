<?php
/**
 * A guestbook controller as an example to show off some basic controller and model-stuff.
 * 
 * @package LydiaCore
 */
class CCGuestbook extends CObject implements IController {

  private $pageTitle = 'Lydia Guestbook Example';
  private $pageHeader = '<h1>Guestbook Example</h1><p>Showing off how to implement a guestbook in Lydia.</p>';
  private $pageMessages = '<h2>Current messages</h2>';
  

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }
  

  /**
   * Implementing interface IController. All controllers must have an index action.
   */
  public function Index() {	
    $formAction = $this->request->CreateUrl('guestbook/add');
    $this->pageForm = "
      <form action='{$formAction}' method='post'>
        <p>
          <label>Message: <br/>
          <textarea name='newEntry'></textarea></label>
        </p>
        <p>
          <input type='submit' name='doAdd' value='Add message' />
          <input type='submit' name='doClear' value='Clear all messages' />
        </p>
      </form>
    ";
    $this->data['title'] = $this->pageTitle;
    $this->data['main']  = $this->pageHeader . $this->pageForm . $this->pageMessages;
    
    if(isset($_SESSION['guestbook'])) {
      foreach($_SESSION['guestbook'] as $val) {
        $this->data['main'] .= "<div style='background-color:#f6f6f6;border:1px solid #ccc;margin-bottom:1em;padding:1em;'><p>At: {$val['time']}</p><p>" . htmlent($val['entry']) . "</p></div>\n";
      }
    }
  }
  

  /**
   * Add a entry to the guestbook.
   */
  public function Add() {
    if(isset($_POST['doAdd'])) {
      $entry = strip_tags($_POST['newEntry']);
      $time = date('r');
      $_SESSION['guestbook'][] = array('time'=>$time, 'entry'=>$entry); 
    }
    elseif(isset($_POST['doClear'])) {
      unset($_SESSION['guestbook']);
    }            
    header('Location: ' . $this->request->CreateUrl('guestbook'));
  }
  

} 
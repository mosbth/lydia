<?php
/**
 * A form to manage books.
 * 
 * @package LydiaCMF
 */
class CFormBook extends CForm {

  /**
   * Constructor
   */
  public function __construct($book) {
    parent::__construct();
    $save = isset($book['id']) ? 'save' : 'create';
    $this->AddElement(new CFormElementHidden('id', array('value'=>$book['id'])))
         ->AddElement(new CFormElementText('title', array('value'=>$book['title'])))
         ->AddElement(new CFormElementText('key', array('value'=>$book['key'])))
         ->AddElement(new CFormElementSubmit($save, array('callback'=>array($this, 'DoSave'), 'callback-args'=>array($book))))
         ->AddElement(new CFormElementSubmit('delete', array('callback'=>array($this, 'DoDelete'), 'callback-args'=>array($book))));

    $this->SetValidation('title', array('not_empty'))
         ->SetValidation('key', array('not_empty'));
  }
  

  /**
   * Callback to save the form content to database.
   */
  public function DoSave($form, $book) {
    $book['id']     = $form['id']['value'];
    $book['title']  = $form['title']['value'];
    $book['key']    = $form['key']['value'];
    return $book->Save();
  }
  
  
  /**
   * Callback to delete the content.
   */
  public function DoDelete($form, $book) {
    $book['id'] = $form['id']['value'];
    $book->Delete();
    CLydia::Instance()->RedirectTo('book');
  }
  
  
}

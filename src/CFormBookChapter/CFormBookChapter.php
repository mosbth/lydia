<?php
/**
 * A form to manage book chapters.
 * 
 * @package LydiaCMF
 */
class CFormBookChapter extends CForm {

  /**
   * Constructor
   */
  public function __construct($book) {
    parent::__construct();

    $this->AddElement(new CFormElementHidden('bookid', array('value'=>$book['id'])))
         ->AddElement(new CFormElementText('position', array('value'=>$book['chapter']->position)))
         ->AddElement(new CFormElementText('content', array('value'=>$book['chapter']->idContent)))
         ->SetValidation('position', array('not_empty', 'numeric'))
         ->SetValidation('content', array('not_empty', 'numeric'));

    if(isset($book['chapter']->id)) {
      $this->AddElement(new CFormElementHidden('chapterid', array('value'=>$book['chapter']->id)))
           ->AddElement(new CFormElementSubmit('save', array('callback'=>array($this, 'DoSave'), 'callback-args'=>array($book))))
           ->AddElement(new CFormElementSubmit('delete', array('callback'=>array($this, 'DoDelete'), 'callback-args'=>array($book))))
           ->SetValidation('chapterid', array('not_empty', 'numeric'));
    } else {
      $this->AddElement(new CFormElementSubmit('add', array('callback'=>array($this, 'DoSave'), 'callback-args'=>array($book))));
    }
  }
  

  /**
   * Callback to save the form content to database.
   */
  public function DoSave($form, $book) {
    $book['chapter']->id          = $form['chapterid']['value'];
    $book['chapter']->position    = $form['position']['value'];
    $book['chapter']->idContent   = $form['content']['value'];
    return $book->SaveCurrentChapter();
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

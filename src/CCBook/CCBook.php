<?php
/**
 * Controller to manage books and its chapters.
 * 
 * @package LydiaCMF
 */
class CCBook extends CObject implements IController {


  /**
   * Constructor
   */
  public function __construct() { parent::__construct(); }


  /**
   * Show a listing of all books.
   */
  public function Index() {
    $books = new CMBook();
    $this->views->SetTitle(t('Books overview'))
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'), array('books' => $books->GetEntries()));
  }
  

  /**
   * Edit a selected book, or prepare to create new book if argument is missing.
   *
   * @param id integer the id of the book.
   */
  public function Edit($id=null) {
    $book = new CMBook($id);
    $form = new CFormBook($book);
    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', t('The form could not be processed.'));
      $this->RedirectToController('edit', $id);
    } else if($status === true) {
      $this->RedirectToController('edit', $book['id']);
    }
    
    $title = isset($id) ? t('Edit book: @title', array('@title'=>$book['title'])) : t('Create new book');
    $this->views->SetTitle($title)
                ->AddIncludeToRegion('primary', $this->LoadView('edit.tpl.php'), array(
                  'user'=>$this->user, 
                  'book'=>$book, 
                  'form'=>$form,
                ));
  }
  

  /**
   * Create new book.
   */
  public function Create() {
    $this->Edit();
  }


  /**
   * Display an overview of the book.
   */
  public function View($id=null) {
    if(!isset($id)) { $this->ShowErrorPage('404', t('No such book.')); }
    $book = new CMBook($id);
    $book->LoadAllChapters();
    $this->views->SetTitle(htmlEnt($book['title']))
                ->AddIncludeToRegion('primary', $this->LoadView('view.tpl.php'), array('user'=>$this->user, 'book'=>$book));
  }
  

  /**
   * Add and edit details on a chapter.
   *
   * @param integer $bookid the id of the book.
   * @param integer $chapterid the id of the chapter.
   */
  public function Chapter($action=null, $bookId=null, $chapterId=null) {
    in_array($action, array('add','edit')) or $this->ShowErrorPage('404', t('No such action.'));
    is_numeric($bookId) or $this->ShowErrorPage('404', t('No such book.'));
    
    $book = new CMBook($bookId);
    if($action == 'add') {
      $book->SetCurrentChapter(null);
    } else if($action == 'edit') {
      is_numeric($chapterId) or $this->ShowErrorPage('404', t('No such book chapter.'));
      $book->LoadAllChapters();
      $book->SetCurrentChapter($chapterId) or $this->ShowErrorPage('404', t('No such book chapter.'));
    }
  
    $form = new CFormBookChapter($book);
    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', t('The form could not be processed.'));
      $this->RedirectToController('chapter/'.$action, $bookId);
    } else if($status === true) {
      //$this->RedirectToController('chapter/edit', $bookId.'/'.$book['chapter']->id);
      $this->RedirectToController('view', $bookId);
    }
    
    $title = isset($id) ? t('Edit book chapter') : t('Add new book chapter');
    $this->views->SetTitle($title)
                ->AddIncludeToRegion('primary', $this->LoadView($action.'_chapter.tpl.php'), array(
                  'action'=>$action, 
                  'user'=>$this->user, 
                  'book'=>$book, 
                  'form'=>$form,
                ));
  }
  

} 
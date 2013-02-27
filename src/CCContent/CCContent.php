<?php
/**
 * A user controller to manage content.
 * 
 * @package LydiaCore
 */
class CCContent extends CObject implements IController {


  /**
   * Constructor
   */
  public function __construct() { 
    parent::__construct(); 
    //$cf = new CInterceptionFilter();
    //$cf->AuthenticatedOrLogin();
    //$cf->AdminOrForbidden();
  }


  /**
   * Show a listing of all content.
   */
  public function Index() {
    $content = new CMContent();

    CInterceptionFilter::OwnerAdminOrForbidden($content);

    $this->views->SetTitle(t('Content Controller'))
                ->AddIncludeToRegion('primary', $this->LoadView('index.tpl.php'), array(
                  'contents' => $content->ListAll(),
                ));
  }
  

  /**
   * Edit a selected content, or prepare to create new content if argument is missing.
   *
   * @param id integer the id of the content.
   */
  public function Edit($id=null) {
    $content = new CMContent($id);

    //CInterceptionFilter::OwnerAdminOrForbidden($content);
    CInterceptionFilter::IsRegularUserOrForbidden();

    $form = new CFormContent($content);
    $status = $form->Check();
    if($status === false) {
      $this->AddMessage('notice', 'The form could not be processed.');
      $this->RedirectToController('edit', $id);
    } else if($status === true) {
      $this->RedirectToController('edit', $content['id']);
    }
    
    $title = isset($id) ? 'Edit' : 'Create';
    $this->views->SetTitle("$title content: ".htmlEnt($content['title']))
                ->AddIncludeToRegion('primary', $this->LoadView('edit.tpl.php'), array(
                  'user'=>$this->user, 
                  'content'=>$content, 
                  'form'=>$form,
                ));
  }
  

  /**
   * Create new content.
   */
  public function Create() {
    $this->Edit();
  }


  /**
   * Init the content database.
   */
/*  public function Init() {
    $content = new CMContent();
    $content->Init();
    $this->RedirectToController();
  }
  */

} 
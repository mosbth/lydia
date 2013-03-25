<?php
/**
 * A form to manage content.
 * 
 * @package LydiaCore
 */
class CFormContent extends CForm {

  /**
   * Properties
   */
  private $content;

  /**
   * Constructor
   */
  public function __construct($content) {
    parent::__construct();
    $this->content = $content;
    $save = isset($content['id']) ? 'save' : 'create';
    $this->AddElement(new CFormElementHidden('id', array('value'=>$content['id'])))
         ->AddElement(new CFormElementText('title', array('value'=>$content['title'])))
         ->AddElement(new CFormElementText('key', array('value'=>$content['key'])))
         ->AddElement(new CFormElementTextarea('data', array('label'=>'Content:', 'value'=>$content['data'])))
         ->AddElement(new CFormElementText('datafile', array('label'=>'Content from file:', 'value'=>$content['datafile'])))
         ->AddElement(new CFormElementText('type', array('value'=>$content['type'])))
         ->AddElement(new CFormElementText('idCategory', array('label'=>t('Category id:'), 'value'=>$content['idCategory'])))
         ->AddElement(new CFormElementText('filter', array('value'=>$content['filter'])))
         ->AddElement(new CFormElementText('url', array('value'=>$content['url'])))
         ->AddElement(new CFormElementText('breadcrumb', array('label' => t('Breadcrumb parent title:'), 'value'=>$content['breadcrumb'])))
         ->AddElement(new CFormElementText('parenttitle', array('label' => t('Parent title:'), 'value'=>$content['parenttitle'])))
         ->AddElement(new CFormElementText('template', array('label' => t('Template:'), 'value'=>$content['template'])))
         ->AddElement(new CFormElementText('published', array('label' => t('Published:'), 'value'=>$content['published'])))
         ->AddElement(new CFormElementSubmit($save, array('callback'=>array($this, 'DoSave'), 'callback-args'=>array($content))))
         ->AddElement(new CFormElementSubmit('delete', array('callback'=>array($this, 'DoDelete'), 'callback-args'=>array($content))));

    $this->SetValidation('title', array('not_empty'))
         ->SetValidation('type', array('not_empty'))
         ->SetValidation('filter', array('not_empty'));
  }
  

  /**
   * Callback to save the form content to database.
   */
  public function DoSave($form, $content) {
    $content['id']     = $form['id']['value'];
    $content['title']  = $form['title']['value'];
    if(empty($form['key']['value'])) {
      $content['key'] = slugify($form['title']['value']);
    } else {
      $content['key'] = $form['key']['value'];
    }
    $content['data']        = $form['data']['value'];
    $content['datafile']    = $form['datafile']->GetValueNullIfEmpty();
    $content['type']        = $form['type']->GetValueNullIfEmpty();
    $content['idCategory']  = $form['idCategory']->GetValueNullIfEmpty();
    $content['filter']      = $form['filter']->GetValueNullIfEmpty();
    $content['url']         = $form['url']->GetValueNullIfEmpty();
    $content['breadcrumb']  = $form['breadcrumb']->GetValueNullIfEmpty();
    $content['parenttitle'] = $form['parenttitle']->GetValueNullIfEmpty();
    $content['template']    = $form['template']->GetValueNullIfEmpty();
    $content['published']   = $form['published']->GetValueNullIfEmpty();
    return $content->Save();
  }
  
  
  /**
   * Callback to delete the content.
   */
  public function DoDelete($form, $content) {
    $content['id'] = $form['id']['value'];
    $content->Delete();
    CLydia::Instance()->RedirectTo('content');
  }
  
  
}

<?php

/**
 * archivos actions.
 *
 * @package    taller
 * @subpackage archivos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class archivosActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->archivoss = Doctrine_Core::getTable('Archivos')
      ->createQuery('a')
      ->execute();

    $this->archivosForm = new ArchivosForm();
    $this->archivosForm->setDefault('fecha', date('Y/m/d'));
  }

  public function executeProcessarchivosFormAjax(sfWebRequest $request)
    {   
        $form = new ArchivosForm();
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid())
        {
            $archivo = $form->save();
             // Set the response format to json
            $this->getResponse()->setHttpHeader('Content-type','application/json');
            
            // Use renderText to return a json encoded array !
            return $this->renderText(json_encode(array(
                "success" => true
            )));
        }
        $this->redirect('archivos/load?id='.$archivo->getId());

    }


    public function executeLoad(sfWebRequest $request){
        $id = $request->getParameter('id');
        $s3Connection = new S3Connection('pabloriascosbucket', array(
                'region'      => sfConfig::get('app_aws_region'),
                'version'     => sfConfig::get('app_aws_version'),
                'credentials' => array(
                    'key'    => sfConfig::get('app_aws_key'),
                    'secret' => sfConfig::get('app_aws_secret'),
                ),
            ));
        $s3FileManager = new S3FileManager($s3Connection);
        $archivoSubir = Doctrine_Core::getTable('Archivos')->findOneBy('archivo',$id);
        
        // $response = array('ok'=>false);
        
          $response = $s3FileManager->uploadFilePublic(sfConfig::get('sf_upload_dir') . "/archivo/" . $archivoSubir->getArchivo(), $archivoSubir->getArchivo());

        // return $this->renderText(json_encode($response));
        $this->redirect('archivos/index');
    }

 

  public function executeShow(sfWebRequest $request)
  {
    $this->archivos = Doctrine_Core::getTable('Archivos')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->archivos);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ArchivosForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ArchivosForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($archivos = Doctrine_Core::getTable('Archivos')->find(array($request->getParameter('id'))), sprintf('Object archivos does not exist (%s).', $request->getParameter('id')));
    $this->form = new ArchivosForm($archivos);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($archivos = Doctrine_Core::getTable('Archivos')->find(array($request->getParameter('id'))), sprintf('Object archivos does not exist (%s).', $request->getParameter('id')));
    $this->form = new ArchivosForm($archivos);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($archivos = Doctrine_Core::getTable('Archivos')->find(array($request->getParameter('id'))), sprintf('Object archivos does not exist (%s).', $request->getParameter('id')));
    $archivos->delete();

    $this->redirect('archivos/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $archivos = $form->save();
      
      $this->redirect('archivos/load?id='.$archivos->getArchivo());
    }
  }
}

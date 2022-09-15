<?php

/**
 * Archivos form.
 *
 * @package    taler
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ArchivosForm extends BaseArchivosForm
{
  public function configure()
  {
      
      $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'archivo'    => new sfWidgetFormInputText(),
      'nombre' => new sfWidgetFormInputText(),
      'fecha'       => new sfWidgetFormDate(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'archivo'    => new sfValidatorString(array('max_length' => 255)),
      'nombre' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'fecha'       => new sfValidatorDate(array('required' => false)),
    ));
    
    $imagen = '/archivo/'.$this->getObject()->archivo;
    $this->widgetSchema['archivo'] = new sfWidgetFormInputFileEditable(array(
        'file_src'  => $imagen,
        'is_image' => true,
        'edit_mode' => !$this->isNew(),
        'template'     => '<style>.fix img{max-width:96px;}</style><div class="fix">%file%</div><br />%input%<br />',
        'delete_label' => 'Borrar imagen'));
     
    //$this->validatorSchema['archivo'] = new sfValidatorFile(array(
    //    'required'   => $this->isNew(),
    //    'path'       => sfConfig::get('sf_upload_dir').'/archivo',
    //    'mime_types' => 'web_images',
    //));
        
        $this->validatorSchema['archivo'] = new sfValidatorFile(array(
        'required'   => false,
        'path'       => sfConfig::get('sf_upload_dir'),
        'path'       => sfConfig::get('sf_upload_dir').'/archivo',
        //'TypeDasbilitarValidacion' => 'application/x-rar-compressed'
        )); 

    $this->widgetSchema->setNameFormat('archivos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
      
  }
}

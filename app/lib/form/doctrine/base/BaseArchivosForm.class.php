<?php

/**
 * Archivos form base class.
 *
 * @method Archivos getObject() Returns the current form's model object
 *
 * @package    taller
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseArchivosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'fecha'   => new sfWidgetFormDateTime(),
      'nombre'  => new sfWidgetFormInputText(),
      'archivo' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'fecha'   => new sfValidatorDateTime(array('required' => false)),
      'nombre'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'archivo' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('archivos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Archivos';
  }

}

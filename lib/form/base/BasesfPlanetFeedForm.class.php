<?php

/**
 * sfPlanetFeed form base class.
 *
 * @package    form
 * @subpackage sf_planet_feed
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BasesfPlanetFeedForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'title'           => new sfWidgetFormInput(),
      'slug'            => new sfWidgetFormInput(),
      'description'     => new sfWidgetFormTextarea(),
      'homepage'        => new sfWidgetFormInput(),
      'feed_url'        => new sfWidgetFormInput(),
      'is_active'       => new sfWidgetFormInputCheckbox(),
      'periodicity'     => new sfWidgetFormInput(),
      'last_grabbed_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'sfPlanetFeed', 'column' => 'id', 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'slug'            => new sfValidatorString(array('max_length' => 255)),
      'description'     => new sfValidatorString(array('required' => false)),
      'homepage'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'feed_url'        => new sfValidatorString(array('max_length' => 255)),
      'is_active'       => new sfValidatorBoolean(array('required' => false)),
      'periodicity'     => new sfValidatorInteger(),
      'last_grabbed_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_planet_feed[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfPlanetFeed';
  }


}

<?php

/**
 * sfPlanetFeedEntry form base class.
 *
 * @package    form
 * @subpackage sf_planet_feed_entry
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BasesfPlanetFeedEntryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'feed_id'      => new sfWidgetFormPropelSelect(array('model' => 'sfPlanetFeed', 'add_empty' => false)),
      'title'        => new sfWidgetFormInput(),
      'content'      => new sfWidgetFormTextarea(),
      'link_url'     => new sfWidgetFormInput(),
      'unique_id'    => new sfWidgetFormInput(),
      'created_at'   => new sfWidgetFormDateTime(),
      'published_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorPropelChoice(array('model' => 'sfPlanetFeedEntry', 'column' => 'id', 'required' => false)),
      'feed_id'      => new sfValidatorPropelChoice(array('model' => 'sfPlanetFeed', 'column' => 'id')),
      'title'        => new sfValidatorString(array('max_length' => 255)),
      'content'      => new sfValidatorString(array('required' => false)),
      'link_url'     => new sfValidatorString(array('max_length' => 255)),
      'unique_id'    => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'created_at'   => new sfValidatorDateTime(array('required' => false)),
      'published_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_planet_feed_entry[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfPlanetFeedEntry';
  }


}

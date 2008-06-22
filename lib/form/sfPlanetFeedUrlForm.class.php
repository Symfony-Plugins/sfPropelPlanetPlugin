<?php
/**
 * Simple form with just one input field for feed url. This one doesn't extends 
 * sfPlanetFeedForm to prevent loading the heavy sfPropelForm stuff.
 *
 */
class sfPlanetFeedUrlForm extends sfForm
{
  
  public function configure()
  {
    $this->setDefaults(array(
      'feed_url'    => 'http://',
      'periodicity' => 86400,
      'fetch'       => true,
      'activate'    => true,
    ));
    
    $this->setWidgets(array(
      'feed_url'    => new sfWidgetFormInput(),
      'fetch'       => new sfWidgetFormInputCheckbox(),
      'periodicity' => new sfWidgetFormSelect(array('choices' => sfConfig::get('app_planet_periodicity', array()))),
      'activate'    => new sfWidgetFormInputCheckbox(),
    ));
    
    $this->setValidators(array(
      'feed_url'    => new sfValidatorUrl(),
      'fetch'       => new sfValidatorPass(),
      'periodicity' => new sfValidatorChoice(array('choices' => array_keys(sfConfig::get('app_planet_periodicity', array())))),
      'activate'    => new sfValidatorPass(),
    ));
    
    $this->getWidgetSchema()->setNameFormat('sf_planet_feed[%s]');
  }
  
}

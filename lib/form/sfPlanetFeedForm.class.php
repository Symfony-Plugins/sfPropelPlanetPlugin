<?php
/**
 * sfPlanetFeed form.
 *
 * @package    form
 * @subpackage sf_planet_feed
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class sfPlanetFeedForm extends BasesfPlanetFeedForm
{
  public function configure()
  {
    $this->widgetSchema['title']       = new sfWidgetFormInput();
    $this->widgetSchema['description'] = new sfWidgetFormTextarea();
    $this->widgetSchema['homepage']    = new sfWidgetFormInput();
    $this->widgetSchema['feed_url']    = new sfWidgetFormInput();
    $this->widgetSchema['is_active']   = new sfWidgetFormInputCheckbox();
    $this->widgetSchema['periodicity'] = new sfWidgetFormSelect(array('choices' => sfConfig::get('app_planet_periodicity', array())));
    
    $this->validatorSchema['title']    = new sfValidatorString(array('min_length' => 1, 'max_length' => 255));
    $this->validatorSchema['homepage'] = new sfValidatorOr(array(
      new sfValidatorString(array('max_length' => 255, 'required' => false)),
      new sfValidatorUrl(array('required' => false)),
    ));
    $this->validatorSchema['feed_url'] = new sfValidatorUrl();
    $this->validatorSchema['periodicity'] = new sfValidatorChoice(array('choices' => array_keys(sfConfig::get('app_planet_periodicity', array()))));

    unset($this['last_grabbed_at'], $this['id'], $this['slug']);
  }
}

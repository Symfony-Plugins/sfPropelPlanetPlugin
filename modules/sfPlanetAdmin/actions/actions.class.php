<?php

class sfPlanetAdminActions extends autosfPlanetAdminActions
{
  
  public function preExecute()
  {
    $this->periodicity = sfConfig::get('app_planet_periodicity', array());
  }
  
}

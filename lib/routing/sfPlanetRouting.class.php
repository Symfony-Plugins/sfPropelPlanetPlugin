<?php
/*
planet_feed_home:
  url:   /planet/:slug/home/:page.:sf_format
  param: { module: sfPlanet, action: listFeed }
*/
class sfPlanetRouting extends sfPatternRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    // planet home and pagination
    $r->prependRoute('sf_planet_home', '/planet/home/:page.:sf_format', 
                     array('module'    => 'sfPlanet', 
                           'action'    => 'list', 
                           'sf_format' => 'html', 
                           'page'      => 1));
    
    // planet feed home                 
    $r->prependRoute('sf_planet_feed_home', '/planet/site/:slug/:page.:sf_format', 
                     array('module'    => 'sfPlanet', 
                           'action'    => 'listFeed', 
                           'sf_format' => 'html', 
                           'page'      => 1));
  }
}
  
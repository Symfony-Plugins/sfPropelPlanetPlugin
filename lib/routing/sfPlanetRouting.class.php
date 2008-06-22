<?php
/**
 * sfPropelPlanetPlugin routing hooks. Creates and bundles custom routes for
 * the sfPlanet web module.
 *
 * @package    sfPropelPlanetPlugin
 * @subpackage routing
 */
class sfPlanetRouting extends sfPatternRouting
{
  /**
   * Listens to the routing.load_configuration event and prepend two routes:
   *  - sf_planet_home:      The base route for whole planet entries
   *  - sf_planet_feed_home: The base route for a particular feed entries
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    // planet home and pagination
    $r->prependRoute('sf_planet_home', '/planet/:page.:sf_format', 
                     array('module'    => 'sfPlanet', 
                           'action'    => 'list', 
                           'sf_format' => 'html', 
                           'page'      => 1));
    
    // planet feed home                 
    $r->prependRoute('sf_planet_feed_home', '/planet/:slug/:page.:sf_format', 
                     array('module'    => 'sfPlanet', 
                           'action'    => 'listFeed', 
                           'sf_format' => 'html', 
                           'page'      => 1));
  }
}
  
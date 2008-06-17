<?php
/**
 * Subclass for representing a row from the 'sf_feed' table.
 *
 * @package plugins.sfPropelPlanetPlugin.lib.model
 */ 
class sfPlanetFeed extends BasesfPlanetFeed
{
}

$columns_map = array('from' => BasesfPlanetFeedPeer::TITLE,
                     'to'   => BasesfPlanetFeedPeer::SLUG);

sfPropelBehavior::add('sfPlanetFeed', 
                      array('sfPropelActAsSluggableBehavior' => 
                            array('columns'   => $columns_map, 
                                  'separator' => '-', 
                                  'permanent' => true)));
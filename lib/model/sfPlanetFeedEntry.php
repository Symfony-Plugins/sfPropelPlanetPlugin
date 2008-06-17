<?php
/**
 * Subclass for representing a row from the 'sf_feed_entry' table.
 *
 * @package plugins.sfPropelPlanetPlugin.lib.model
 */ 
class sfPlanetFeedEntry extends BasesfPlanetFeedEntry
{
  
  /**
   * Saves the feed entry, generating a unique id from some of the existing 
   * properties if it not exists
   *
   * @param  Connection $con
   * @return int
   */
  public function save($con = null)
  {
    if (!$this->getUniqueId())
    {
      $this->setUniqueId(md5($this->getPublishedAt() . $this->getLinkUrl()));
    }
    return parent::save($con);
  }
  
}

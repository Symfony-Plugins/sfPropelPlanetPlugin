<?php
class sfPlanetComponents extends sfComponents
{
  /**
   * Lists existing active feeds
   *
   */
  public function executeListFeeds()
  {
    $c = new Criteria();
    $c->add(sfPlanetFeedPeer::IS_ACTIVE, true);
    $c->addAscendingOrderByColumn(sfPlanetFeedPeer::TITLE);
    $this->feeds = sfPlanetFeedPeer::doSelect($c);
  }
}

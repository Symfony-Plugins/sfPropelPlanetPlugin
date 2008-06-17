<?php
/**
 * sfPlanet module actions
 *
 */
class sfPlanetActions extends sfActions
{
  
  /**
   * Lists last entries
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfWebRequest $request)
  {
    $this->entries = sfPlanetFeedEntryPeer::getList($to = null, $from = null, $feed = null);
    if ('rss' === $request->getParameter('sf_format'))
    {
      $this->setLayout(false);
      sfConfig::set('sf_web_debug', false);
      $this->getResponse()->setHttpHeader('content-type', 'text/xml;charset=utf-8');
    }
  }
  
}

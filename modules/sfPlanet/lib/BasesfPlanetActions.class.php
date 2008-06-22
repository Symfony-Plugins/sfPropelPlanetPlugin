<?php
/**
 * sfPlanet module actions
 *
 */
class BasesfPlanetActions extends sfActions
{
  
  /**
   * This code will be executed before all actions
   *
   */
  public function preExecute()
  {
    $this->getResponse()->addStylesheet('/sfPropelPlanetPlugin/css/sf_planet.css');
  }
  
  /**
   * Lists last entries
   *
   * @param    sfWebRequest $request
   * @sfparam  int          $page  The page to display (optional)
   * @sfparam  string       $slug  The slug of the feed to display (optional)
   */
  public function executeList(sfWebRequest $request)
  {
    $this->feed = null;
    
    if ($request->hasParameter('slug'))
    {
      $this->feed = sfPlanetFeedPeer::retrieveBySlug($request->getParameter('slug'));
    }
    
    $this->pager = new sfPropelPager('sfPlanetFeedEntry', sfConfig::get('app_planet_max_per_page', 10));
    $this->pager->setCriteria(sfPlanetFeedEntryPeer::getActiveListCriteria($this->feed));
    $this->pager->setPeerMethod('doSelectJoinsfPlanetFeed');
    $this->pager->setPeerCountMethod('doCountJoinsfPlanetFeed');
    $this->pager->setPage($request->getParameter('page'));
    $this->pager->init();
    
    if ('rss' === $request->getParameter('sf_format'))
    {
      $this->setLayout(false);
      sfConfig::set('sf_web_debug', false);
      $this->getResponse()->setHttpHeader('content-type', 'text/xml;charset=utf-8');
    }
  }
  
  /**
   * List entries for a given feed
   *
   * @param     sfWebRequest $request
   * @sf_param  string       $slug
   */
  public function executeListFeed(sfWebRequest $request)
  {
    $this->forward('sfPlanet', 'list');
  }
  
}

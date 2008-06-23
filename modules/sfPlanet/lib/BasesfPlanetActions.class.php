<?php
/**
 * sfPlanet module base actions
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
    $this->getResponse()->addStylesheet(sfConfig::get('app_planet_css_theme', '/sfPropelPlanetPlugin/css/sf_planet.css'));
    
    if ('rss' === $this->getRequest()->getParameter('sf_format'))
    {
      // Deactivate layout for RSS content (we're in a plugin, so no layout is available)
      $this->setLayout(false);
      // Also, cannot see why web_debug is displayed here but eh
      sfConfig::set('sf_web_debug', false); 
    }
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
      if (is_null($this->feed))
      {
        $message = sprintf('Feed "%s" not found on this planet.', 
                           $request->getParameter('slug'));
        if ('rss' === $request->getParameter('sf_format'))
        {
          $this->getResponse()->setStatusCode(404);
          return $this->renderText($message);
        }
        else
        {
          $this->forward404($message);
        }
      }
    }
    
    $this->pager = new sfPropelPager('sfPlanetFeedEntry', sfConfig::get('app_planet_max_per_page', 10));
    $this->pager->setCriteria(sfPlanetFeedEntryPeer::getActiveListCriteria($this->feed));
    $this->pager->setPeerMethod('doSelectJoinsfPlanetFeed');
    $this->pager->setPeerCountMethod('doCountJoinsfPlanetFeed');
    $this->pager->setPage($request->getParameter('page'));
    $this->pager->init();
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

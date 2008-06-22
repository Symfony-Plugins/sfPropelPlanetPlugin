<?php
/**
 * sfPropelPlanet plugin admin actions
 *
 */
class BasesfPlanetAdminActions extends autosfPlanetAdminActions
{
  
  /**
   * Executed before every action call
   *
   */
  public function preExecute()
  {
    sfLoader::loadHelpers('I18n');
    $this->getResponse()->addStylesheet('/sfPropelPlanetPlugin/css/sf_planet_admin.css');
    $this->periodicity = sfConfig::get('app_planet_periodicity', array());
  }
  
  /**
   * Creates and saves a feed from a feed url
   *
   * @param     sfWebRequest $request
   * @sf_param  string       $url
   */
  public function executeCreateFromFeedUrl(sfWebRequest $request)
  {
    $this->form = new sfPlanetFeedUrlForm();
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('sf_planet_feed'));
      
      if ($this->form->isValid() && $values = $this->form->getValues())
      {
        try
        {
          $feed = sfPlanetFeedPeer::createFromWeb($values['feed_url'], $values['periodicity'], $values['activate']);
          
          if ($values['fetch'])
          {
            $numEntries = $feed->fetchEntries();
          }
        }
        catch (Exception $e)
        {
          $this->getUser()->setFlash('warning', __('Unable to create feed: %msg%', 
                                                   array('%msg%' => $e->getMessage())));
          return sfView::SUCCESS;
        }
        
        $this->getUser()->setFlash('notice', __('Feed successfully created%fetched%', 
                                                array('%fetched%' => !$values['fetch'] ? 
                                                                       __(', no entry fetched') : 
                                                                       __(', %nb% entries fetched', 
                                                                          array('%nb%' => $numEntries)))));
        $this->redirect('sfPlanetAdmin/index');
      }
    }
  }
  
  /**
   * Fetch feed entries
   *
   * @param     sfWebRequest $request
   * @sf_param  int          $id
   */
  public function executeFetchFeed(sfWebRequest $request)
  {
    $feed = sfPlanetFeedPeer::retrieveByPK($request->getParameter('id'));
    
    try
    {
      $numEntries = $feed->fetchEntries();
    }
    catch (Exception $e)
    {
      $this->getUser()->setFlash('warning', __('Unable to fetch feed entries: %msg%', 
                                               array('%msg%' => $e->getMessage())));
      $this->setTemplate('index');
      return sfView::SUCCESS;
    }
    
    $this->getUser()->setFlash('notice', __('Feed successfully fetched, %nb% entries added or updated', 
                                            array('%nb%' => $numEntries)));
    $this->redirect('sfPlanetAdmin/index');
  }
  
  /**
   * Fecthes all outdated feeds
   *
   * @param sfWebRequest $request
   */
  public function executeFetchOutdatedFeeds(sfWebRequest $request)
  {
    try
    {
      sfPlanetFeedPeer::fetchOutdated();
    }
    catch (Exception $e)
    {
      $this->getUser()->setFlash('warning', __('Unable to fetch outdated feeds: %msg%', 
                                               array('%msg%' => $e->getMessage())));
      $this->setTemplate('index');
      return sfView::SUCCESS;
    }
    
    $this->getUser()->setFlash('notice', __('Outdated feeds updated'));
    
    $this->redirect('sfPlanetAdmin/index');
  }
  
}

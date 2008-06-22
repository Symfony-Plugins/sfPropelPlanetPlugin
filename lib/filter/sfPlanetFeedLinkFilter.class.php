<?php
/**
 * sfPropelPlanetPlugin filter that smashes your planet feed link into your <head/> 
 *
 */
class sfPlanetFeedLinkFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    $filterChain->execute();
    
    if ($this->isFirstCall() && 'html' === $this->context->getRequest()->getRequestFormat())
    {
      $response = $this->context->getResponse();
      $content = $response->getContent();
      if (false !== ($pos = strpos($content, '</head>')))
      {
        sfLoader::loadHelpers(array('Tag', 'Asset'));
        $html = auto_discovery_link_tag('rss', '@sf_planet_home?sf_format=rss', array('title' => sfConfig::get('app_planet_title', 'RSS feed')));
        $response->setContent(substr($content, 0, $pos).$html.substr($content, $pos));
      }
    }
  }
}

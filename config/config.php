<?php
// Plugin dependencies check by classes
$dependencies = array(
  'sfPropelActAsSluggableBehaviorPlugin' => 'sfPropelActAsSluggableBehavior',
  'sfWebBrowser' => 'sfWebBrowser',
  'sfFeed2Plugin' => 'sfFeed'
);

foreach ($dependencies as $pluginName => $className)
{
  if (!class_exists($className, true))
  {
    throw new sfConfigurationException(sprintf('sfPropelPlanetPlugin: plugin %s is required (class %s not found). If you\'ve just installed it, did you clear your cache?',
                                               $pluginName, $className));
  }
}

// Plugin dependencies by helpers
try
{
  sfLoader::loadHelpers('PagerNavigation');
}
catch (Exception $e)
{
  throw new sfConfigurationException('sfPropelPlanetPlugin: Plugin sfPagerNavigationPlugin is required. If you\'ve just installed it, did you clear your cache?');
}

// Routing hook
if (sfConfig::get('app_planet_routes_register', true) && in_array('sfPlanet', sfConfig::get('sf_enabled_modules', array())))
{
  $this->dispatcher->connect('routing.load_configuration', array('sfPlanetRouting', 'listenToRoutingLoadConfigurationEvent'));
}
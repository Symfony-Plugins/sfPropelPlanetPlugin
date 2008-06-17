<?php
// Plugin dependencies check
$dependencies = array('sfPropelActAsSluggableBehaviorPlugin' => 'sfPropelActAsSluggableBehavior',
                      'sfWebBrowser' => 'sfWebBrowser',
                      'sfFeed2Plugin' => 'sfFeed');
foreach ($dependencies as $pluginName => $className)
{
  if (!class_exists($className, true))
  {
    throw new sfConfigurationException(sprintf('Plugin %s is required (class %s not found)',
                                                 $pluginName, $className));
  }
}
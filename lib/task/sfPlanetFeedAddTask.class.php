<?php
/**
 * Task to add a feed to the planet
 *
 */
class sfPlanetFeedAddTask extends sfPlanetBaseTask 
{
  
  /**
   * Task config
   *
   */
  public function configure()
  {
    $this->aliases          = array('planet-feed-add');
    $this->namespace        = 'planet';
    $this->name             = 'feed-add';
    $this->briefDescription = 'Adds a new feed to the planet';
    $this->detailedDescription = <<<EOF
The [planet:feed-add|INFO] allows you to add and grab a new feed to your planet.

To add a feed to your planet and grab its entries, type for example:

  $ php symfony planet:feed-add http://rss.slashdot.org/Slashdot/slashdot

If you don't want to fetch entries at all:

  $ php symfony planet:feed-add [-s|COMMENT] [http://rss.slashdot.org/Slashdot/slashdot|INFO]

If you want to set the peremption time for your feed to two hours (7200 seconds):

  $ php symfony planet:feed-add [-p 7200|COMMENT] [http://rss.slashdot.org/Slashdot/slashdot|INFO]

If you just want to fetch the last 5 entries from your feed:

  $ php symfony planet:feed-add [-m 5|COMMENT] [http://rss.slashdot.org/Slashdot/slashdot|INFO]

If you want the two features above in one command call:

  $ php symfony planet:feed-add [-p 7200 -m 5|COMMENT] [http://rss.slashdot.org/Slashdot/slashdot|INFO]
EOF;
    
    $this->addArguments(array(
      new sfCommandArgument('feed-url', sfCommandArgument::REQUIRED, 'The feed url (rss, atom) to grab and add to the planet'),
      new sfCommandArgument('application', sfCommandArgument::OPTIONAL, 'The application name', self::getDefaultAppName()),
    ));
    
    $this->addOptions(array(
      new sfCommandOption('non-activate', 'i', sfCommandOption::PARAMETER_NONE, 'Do not activate the feed after saving it'),
      new sfCommandOption('periodicity', 'p', sfCommandOption::PARAMETER_OPTIONAL, 'Peremption time in seconds', 86400),
      new sfCommandOption('no-grab', 's', sfCommandOption::PARAMETER_NONE, 'Do not grab and store the feed entries'),
      new sfCommandOption('max-entries', 'm', sfCommandOption::PARAMETER_OPTIONAL, 'Max number of entries to grab and store', 0),
      
    ));
  }
  
  /**
   * Task execution
   *
   * @param array $arguments
   * @param array $options
   */
  protected function execute($arguments = array(), $options = array())
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration($arguments['application'], 'cli', true);
    $databaseManager = new sfDatabaseManager($configuration);
    
    $url = $arguments['feed-url'];
    $this->logSection('add', $url);
    
    try
    {
      $newFeed = sfPlanetFeedPeer::createFromWeb($url, $options['periodicity'], !$option['non-activate']);
    }
    catch (PropelException $e)
    {
      throw new sfCommandException(sprintf('Unable to create ad store feed from url %s: %s', $url, $e->getMessage()));
    }
    catch (Exception $e)
    {
      throw new sfCommandException(sprintf('No feed can be retrieved from url %s: %s', $url, $e->getMessage()));
    }
    
    $this->logSection('feed', sprintf('added feed "%s"', $newFeed->getSlug()));
    
    // Grab entries
    if (!$options['no-grab'])
    {
      $numEntries = $newFeed->fetchEntries($options['max-entries']);
      
      if ($numEntries > 0)
      {
        $this->logSection('result', sprintf('saved %d entries for feed %s', $numEntries, $newFeed->getSlug()));
      }
      else
      {
        $this->logSection('result', 'no entry found');
      }
    }
  }
  
}
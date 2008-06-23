<?php
/**
 * Feeds purge task
 *
 */
class sfPlanetFeedPurgeTask extends sfPlanetBaseTask
{
  
  /**
   * Task config
   *
   */
  public function configure()
  {
    $this->aliases          = array('planet-feed-purge');
    $this->namespace        = 'planet';
    $this->name             = 'feed-purge';
    $this->briefDescription = 'Purge old planet feeds entries from the database';
    $this->detailedDescription = <<<EOF
The [planet:feed-purge|INFO] purges feed entries older than a given date or 
older than a given amount of time from the database.

If you want to purges feeds entries older than a given date:

  $ php symfony planet:feed-purge [-d 2008-01-01|COMMENT]

If you want to purge feeds entries older than a given amount of time, in 
seconds:

  $ php symfony planet:feed-purge [-s 86400|COMMENT]
EOF;
    
    $this->addArguments(array(
      new sfCommandArgument('feed-slug', sfCommandArgument::OPTIONAL, 'The slug of the feed you want to purge entries from (if not provided, old entries of all feeds will be purged)'),
      new sfCommandArgument('application', sfCommandArgument::OPTIONAL, 'The application name', self::getDefaultAppName()),
    ));
    
    $this->addOptions(array(
      new sfCommandOption('older-than-date', 'd', sfCommandOption::PARAMETER_OPTIONAL, 'All entries published before this date will be purged'),
      new sfCommandOption('older-than-time', 's', sfCommandOption::PARAMETER_OPTIONAL, 'All entries older than this amount of seconds will be purged'),      
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
    
    if (!$options['older-than-date'] && !$options['older-than-time'])
    {
      throw new sfCommandException('You must either provide a date or an amount of time in seconds, see help');
    }
    
    $timestamp = null;
    
    if ($options['older-than-date'])
    {
      $timestamp = (int) strtotime($options['older-than-date']);
      
      if ($timestamp <= 0)
      {
        throw new sfCommandException('The date you provided is invalid, see help');
      }
    }
    
    if ($options['older-than-time'])
    {
      if (!is_null($timestamp))
      {
        throw new sfCommandException('You cannot provide both a date and a time, see help');
      }
      
      if (!is_numeric($options['older-than-time']) or $options['older-than-time'] < 1)
      {
        throw new sfCommandException('The amount of time you provided is invalid, see help');
      }
      
      $timestamp = time() - $options['older-than-time'];
    }
    
    if (is_null($timestamp))
    {
      throw new sfCommandException('No valid date or time provided, see help');
    }
    
    $n = 0;
    
    if ($slug = $arguments['feed-slug'])
    {
      if (is_null($feed = sfPlanetFeedPeer::retrieveBySlug($slug)))
      {
        throw new sfCommandException(sprintf('Feed "%s" does not exist', $slug));
      }
      
      $n = $this->purgeFeedEntries($timestamp, $feed);
    }
    else
    {
      $n = $this->purgeEntries($timestamp);
    }
    
    $this->logSection('result', ($n > 1 ? $n.' entries' : ($n > 0 ? 'one entry' : 'no entry')) . ' deleted');
  }
  
}
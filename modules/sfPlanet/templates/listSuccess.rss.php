<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<rss version="2.0">
  <channel>
    <title><?php echo sfConfig::get('app_planet_title', 'My Planet') ?></title>
    <link><?php echo url_for('@sf_planet_home', true) ?></link>
    <?php if (sfConfig::get('app_planet_description')): ?>
      <description><?php echo sfConfig::get('app_planet_description') ?></description>
    <?php endif; ?>
    <language><?php echo sfConfig::get('app_planet_language', 'en-us') ?></language>
    <pubDate><?php echo date('c') ?></pubDate>
    <lastBuildDate><?php echo date('c') ?></lastBuildDate>
    <docs>http://blogs.law.harvard.edu/tech/rss</docs>
    <generator><?php echo sfConfig::get('app_planet_generator', 'sfPropelPlanetPlugin') ?></generator>
    <?php if (sfConfig::get('app_planet_editor_email')): ?>
      <managingEditor><?php echo sfConfig::get('app_planet_editor_email') ?></managingEditor>
    <?php endif; ?>
    <?php if (sfConfig::get('app_planet_webmaster_email')): ?>
      <webMaster><?php echo sfConfig::get('app_planet_webmaster_email') ?></webMaster>
    <?php endif; ?>
    <?php foreach ($pager->getResults() as $entry): ?>
    <item>
      <?php
        $feed = $entry->getsfPlanetFeed();
        $feed_title = $feed->getTitle();
        $feed_url   = $feed->getFeedUrl();
      ?>
      <title><![CDATA[<?php echo $entry->getTitle(ESC_RAW) ?> (from <?php echo $feed_title ?>)]]></title>
      <link><?php echo $entry->getLinkUrl() ?></link>
      <source url="<?php echo $feed_url ?>"><?php echo $feed_title ?></source>
      <?php if ($entry->getAuthor()): ?>
      <dc:author><![CDATA[<?php echo $entry->getAuthor() ?>]]></dc:author>
      <?php endif; ?>
      <description><![CDATA[<?php echo $entry->getContent(ESC_RAW) ?>]]></description>
      <pubDate><?php echo date('c', $entry->getPublishedAt(null)) ?></pubDate>
      <guid isPermaLink="false">urn:md5:<?php echo md5($entry->getLinkUrl()) ?></guid>
    </item>
    <?php endforeach; ?>
  </channel>
</rss>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<rss version="2.0">
  <channel>
    <title><?php echo sfConfig::get('app_planet_title', 'My Planet') ?></title>
    <link><?php echo sfConfig::get('app_planet_homepage', url_for('@homepage')) ?></link>
    <?php if (sfConfig::get('app_planet_description')): ?>
      <description><?php echo sfConfig::get('app_planet_description') ?></description>
    <?php endif; ?>
    <language><?php echo sfConfig::get('app_planet_language', 'en-us') ?></language>
    <pubDate><?php echo date('c') ?></pubDate>
    <lastBuildDate><?php echo date('c') ?></lastBuildDate>
    <docs>http://blogs.law.harvard.edu/tech/rss</docs>
    <generator>sfPropelPlanetPlugin</generator>
    <?php if (sfConfig::get('app_planet_editor_email')): ?>
      <managingEditor><?php echo sfConfig::get('app_planet_editor_email') ?></managingEditor>
    <?php endif; ?>
    <?php if (sfConfig::get('app_planet_webmaster_email')): ?>
      <webMaster><?php echo sfConfig::get('app_planet_webmaster_email') ?></webMaster>
    <?php endif; ?>
    <?php foreach ($entries as $entry): ?>
    <item>
      <title><?php echo $entry->getTitle() ?> (<?php echo $entry->getsfPlanetFeed()->getTitle() ?>)</title>
      <link><?php echo $entry->getLinkUrl() ?></link>
      <description><?php echo $entry->getContent() ?></description>
      <pubDate><?php echo date('G', $entry->getPublishedAt(null)) ?></pubDate>
      <guid isPermaLink="false">urn:md5:<?php echo md5($entry->getLinkUrl()) ?></guid>
    </item>
    <?php endforeach; ?>
  </channel>
</rss>
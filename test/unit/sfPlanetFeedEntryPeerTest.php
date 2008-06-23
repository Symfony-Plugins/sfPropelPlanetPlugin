<?php
$app = 'frontend';
require_once(dirname(__FILE__).'/../../../../test/bootstrap/functional.php');
require_once(dirname(__FILE__).'/../../../../lib/vendor/symfony/lib/vendor/lime/lime.php');

$t = new lime_test(5, new lime_output_color());

$i1 = new sfFeedItem(array(
  'link'    => 'http://foo.bar/entry1',
  'pubDate' => strtotime('yesterday'),
));

$f1 = new sfPlanetFeed();
$f1->setTitle('slashdot');
$f1->save();

$t->diag('createFromFeedItem()');
$e1 = sfPlanetFeedEntryPeer::createFromFeedItem($i1, $f1);
$t->isa_ok($e1, 'sfPlanetFeedEntry', 'createFromFeedItem() returns a sfPlanetFeedEntry');
$t->is($e1->getLinkUrl(), $i1->getLink(), 'createFromFeedItem() mirrors properties');
$e1->setFeedId($f1->getId());
$e1->save();

$t->diag('getOrCreateFromFeedItem()');
$e1 = sfPlanetFeedEntryPeer::getOrCreateFromFeedItem($i1);
$t->isa_ok($e1, 'sfPlanetFeedEntry', 'getOrCreateFromFeedItem() returns a sfPlanetFeedEntry');
$t->ok(!$e1->isNew(), 'getOrCreateFromFeedItem() retrieves existing entry if exists in database');

$t->diag('doDeleteOlderThan()');
sleep(1);
$t->is(sfPlanetFeedEntryPeer::doDeleteOlderThan(time() - 1), 1, 'doDeleteOlderThan() deletes entry older than given timestamp');

$f1->delete();
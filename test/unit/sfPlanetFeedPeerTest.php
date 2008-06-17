<?php
$app = 'frontend';
require_once(dirname(__FILE__).'/../../../../test/bootstrap/functional.php');
require_once(dirname(__FILE__).'/../../../../lib/vendor/symfony/lib/vendor/lime/lime.php');

$t = new lime_test(3, new lime_output_color());

$t->diag('creates a perempted feed');
$f1 = new sfPlanetFeed();
$f1->setTitle('slashdot');
$f1->setLastGrabbedAt(strtotime('yesterday'));
$f1->setPeriodicity(3600); // 1 hour
$f1->save();

$perempted = sfPlanetFeedPeer::getPerempted();

$t->isa_ok($perempted, 'array', 'getPerempted() retrieves an array of results');
$t->is(array_pop($perempted)->getTitle(), 'slashdot', 'getPerempted() retrieves perempted feed');

$t->diag('creates an unperempted feed');
$f2 = new sfPlanetFeed();
$f2->setTitle('digg');
$f2->setLastGrabbedAt(strtotime('1 minute ago'));
$f2->setPeriodicity(86400); // 1 day
$f2->save();

$perempted = sfPlanetFeedPeer::getPerempted();

$t->isnt(array_pop($perempted)->getTitle(), 'digg', 'getPerempted() does not retrieve unperempted feed');

$f1->delete();
$f2->delete();
#!/usr/bin/env php
<?php
require_once dirname(__FILE__) . '/../lib/Autoloader/Simple/MapAutoloaderBuilder.php';

$options = getopt('t:');
if (false === isset($options['t'])) {
    throw new Exception('t option required.');
}

Autoloader_Simple_MapAutoloaderBuilder::build($options['t']);

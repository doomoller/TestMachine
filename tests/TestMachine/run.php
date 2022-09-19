<?php

// proyect root
$root = dirname(dirname(__DIR__));
chdir($root);
$test_path = $root . DIRECTORY_SEPARATOR . 'tests';
set_include_path(get_include_path() . PATH_SEPARATOR . $test_path);

require_once('TestMachineRunner.php');

use TestMachine\TestMachine\TestMachineRunner;

$_t = new TestMachineRunner();
$_t->run($test_path);
$_t->summary();




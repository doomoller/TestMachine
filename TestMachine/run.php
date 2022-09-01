<?php

chdir(dirname(__DIR__));

require_once('TestMachineRunner.php');

use TestMachine\TestMachine\TestMachineRunner;

$_t = new TestMachineRunner();
$_t->run(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'test');
$_t->summary();




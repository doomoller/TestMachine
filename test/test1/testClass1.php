<?php

require_once 'TestMachine/TestMachine.php';

use TestMachine\TestMachine\TestMachine;

class testClass1 extends TestMachine
{
    public function testTest1()
    {
        $this->assert(false, 'testTest1');
    }

    public function testTest2()
    {
        $this->assert(true, 'testTest2');
    }

}
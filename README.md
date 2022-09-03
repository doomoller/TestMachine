# TestMachine

Simple Test helper to process custom PHP tests.

To write you test just extends TestMachine, name your test methods starting with "test", and set it public.

File's names must start with "test". Example of simple test class:

<?php

// File: testClass1.php
require_once 'TestMachine/TestMachine.php';

use TestMachine\TestMachine\TestMachine;

class testClass1 extends TestMachine
{
    public function testTest1()
    {
        $this->assert(false, 'testTest1 failded');
    }

    public function testTest2()
    {
        $this->assert(true, 'testTest2 success');
    }

}


Then run with: 

php TestMachine/run.php

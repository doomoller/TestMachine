<?php

namespace TestMachine\TestMachine;

class TestMachineAssertion
{
    public function __construct(
                                public bool     $status,
                                public string   $msg,
                                public string   $file,
                                public string   $method,
                                public string   $class,
                                public int      $line
                                ){}
}

class TestMachine
{
    private array   $assertions = array();

    public function __construct(){}

    public function assert(bool $chk, string $msg = '')
    {
        $error = false;
        $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[0];
        $debug2 = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];

        try {assert($chk);}
        catch(\AssertionError $e) {$error = true;}
    
        $this->assertions[] = new TestMachineAssertion(!$error, $msg, $debug['file'], $debug2['function'], $debug2['class'], $debug['line']);
    }
    public function report(string $method = '') :array
    {
        if($method != '')
        {
            $as = array();
            foreach($this->assertions as $k => $a)
            {
                if($a->method == $method) $as[] = $a;
            }
            return $as;
        }
        return $this->assertions;
    }
   
}


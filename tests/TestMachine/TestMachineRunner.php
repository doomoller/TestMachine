<?php


namespace TestMachine\TestMachine;

/*
*   This class will look for all test classes in the path provided,
*   then, run all test y report it to console.
*   All test classes must be in files with names starting with 'test'.
*   All test classes must extends TestMachine.
*   All test methods must start with 'test' and set as public.
*/
class TestMachineRunner
{
    private array       $reports = array();
    private array       $report_time = array();

    public function __construct()
    {
        assert_options(ASSERT_ACTIVE,   true);
    }
    public function run(string $path = '.')
    {
        $this->reports = array();
        $this->report_time = array();

        $this->consoleHeader('Test start: '. $path);
        
        $this->findTestClasses($path);

        foreach(get_declared_classes() as $k => $class)
        {
            if(is_subclass_of($class, 'TestMachine\TestMachine\TestMachine'))
            {
                echo 'Test class: ' . $class . PHP_EOL;
                $c = new $class;
                foreach(get_class_methods($class) as $test) {
                    if(preg_match('/^test/', $test))
                    {
                        $this->report_time[$class.'::'.$test] = microtime(true);
                        $c->{$test}();
                        $this->report_time[$class.'::'.$test] = microtime(true) - $this->report_time[$class.'::'.$test];
                        $this->report($c->report($test));
                    }
                }
                $this->reports = array_merge($this->reports, $c->report());
            }
        }
    }
    public function report(array $assertions)
    {
        foreach($assertions as $k => $a)
        {
            echo "\033[97mAssert:\t\t \033[0m";

            if($a->status) echo "\033[32mOk\t \033[0m";
            else echo "\033[31mFailed\t \033[0m"; 

            echo "\033[96mTest: ".$a->method."\t\033[0m";

            echo "\033[94mOn: ".$a->class."\t\033[0m";

            if(!$a->status)
            {
                echo " \033[31m Msg: ".$a->msg . " \033[0m ";
                echo " \033[31m Line: ".$a->line . " \033[0m ";
                echo " \033[31m File: ".$a->file . " \033[0m ";
            }
            echo PHP_EOL;
        }
    }
    public function summary()
    {
        $this->consoleHeader('Summary');
        $errors = 0;
        $success = 0;
        $total_time = 0;
        foreach($this->reports as $k => $a)
        {
            $total_time += $this->report_time[$a->class.'::'.$a->method];
            if($a->status) $success++;
            else $errors++;
        }
        echo "\033[93m\tAsserts Failed:\t\t".$errors."\033[0m".PHP_EOL;
        echo "\033[93m\tAsserts Success:\t".$success."\033[0m".PHP_EOL;
        echo "\033[93m\tTotal time:\t\t".number_format($total_time / 1000, 8)." seconds\033[0m".PHP_EOL;
    }
    public function findTestClasses(string $path) :array
    {
        $cls = array();
        foreach(scandir($path) as $k => $f)
        {
            $f = mb_strtolower($f);
            if($f != '.' && $f != '..' && $f != 'testmachine')
            {
                if(is_dir($path . DIRECTORY_SEPARATOR . $f))
                {
                    $cls[$f] = $this->findTestClasses($path . DIRECTORY_SEPARATOR . $f);
                }
                else
                {
                    if(preg_match('/^test.*\.php$/', $f))
                    { 
                        $cls[] = $f;
                        include($path . DIRECTORY_SEPARATOR . $f);
                    }
                }
            }
        }
       
        return $cls;
     }
     private function consoleHeader(string $msg)
     {
        echo "\033[97m--------------------------------------------------------------------------------------------------------\033[0m".PHP_EOL;
        echo "\033[93m\t".$msg."\033[0m".PHP_EOL;
        echo "\033[97m--------------------------------------------------------------------------------------------------------\033[0m".PHP_EOL;
     }
}
<?php
/**
 * Created by Yellow Heroes
 * Project: scratchpad
 * File: index.php
 * User: Robert
 * Date: 01/04/2019
 * Time: 14:13
 * Reference: https://medium.com/async-php/co-operative-php-multitasking-ce4ef52858a0
 */
require "./Scheduler.php";
require "./Job.php";

$scheduler = new Scheduler();

/* --------------------------------- START new code use iife ----------------------- */
// iife format: (function(){})();
$job3 = new Job((function() {
    for($i=0; $i<=3; $i++) {
        echo 'job 3: ' . $i . '<br />';
        sleep(1);
        flush();
        ob_flush();
        yield; // this is a anonymous generator
    }
})());
/* --------------------------------- END new code use iife ----------------------- */


/* --------------------------------- exactly same functionality, but now code uses call_user_func() ----------------------- */
/*
 * Notice how we’re wrapping the generator function within call_user_func()?
 * That’s just a shortcut for defining the generator function and then
 * immediately calling it to get a new generator instance…
 *
 * Remember: when a generator function is invoked, it returns a generator object (iterable object).
 * Generator objects cannot be instantiated via new (source: https://www.php.net/manual/en/class.generator.php).
 */
$job1 = new Job(call_user_func(function() {
    for ($i = 0; $i <= 3; $i++) {
        print "job 1: " . $i . "<br />";
        sleep(1); // a small delay to show the jobs come in one after another
        flush();
        ob_flush();
        yield;
    }
}));

$job2 = new Job(call_user_func(function() {
    for ($i = 0; $i <= 6; $i++) {
        print "job 2: " . $i . "<br />";
        sleep(1); // a small delay to show the jobs come in one after another
        flush();
        ob_flush();
        yield;
    }
}));

$scheduler->enqueue($job1);
$scheduler->enqueue($job2);
$scheduler->enqueue($job3); // uses an iife

$scheduler->run();
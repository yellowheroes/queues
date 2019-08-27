<?php
/**
 * Created by Yellow Heroes
 * Project: scratchpad
 * File: Job.php
 * User: Robert
 * Date: 01/04/2019
 * Time: 10:54
 * Reference: https://medium.com/async-php/co-operative-php-multitasking-ce4ef52858a0
 */

/*
 * Job is a decorator for ordinary generators. We store the generator for later use,
 * and implement run() and finished() methods. run() makes the task tick,
 * while finished() lets the scheduler know when to stop running the task.
 */
class Job
{
    protected $generator = null;
    protected $running = false;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function run()
    {
        /*
         * originally we had:
         * $this->generator->next();
         * but this works out to first run task1 twice,
         * and then run task2 twice, before it alternates
         * the tasks one-after-the other, 1 by 1.
         * So we added: $this->generator->current(); when $running === false.
         */
        if($this->running === false) {
            $this->generator->current(); // Generator::current returns the yielded value
        } else {
            $this->generator->next(); // Resume execution of the generator (it's running)
        }

        $this->running = true;
    }

    public function finished() : bool
    {
        /*
         * valid() returns true if iterator still running.
         */
        $finished = ($this->generator->valid()) ? false : true;
        return $finished; // true when generator finished
    }
}
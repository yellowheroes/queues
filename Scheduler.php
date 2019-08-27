<?php
/**
 * Created by Yellow Heroes
 * Project: scratchpad
 * File: Scheduler.php
 * User: Robert
 * Date: 01/04/2019
 * Time: 11:13
 * Reference: https://medium.com/async-php/co-operative-php-multitasking-ce4ef52858a0
 */
/*
 * Scheduler maintains a queue of running jobs.
 *
 * Scheduler::run() will run all jobs until the queue is empty.
 * It removes a job from the front of the queue and runs it.
 * If the job has not finished after a run,
 * the job is sent back to the back of the queue.
 *
 * SplQueue is ideal for this use case. It runs off a FIFO schedule,
 * i.e. each job will get some processing time.
 *
 * SplQueue inherits from SplDoublyLinkedList.
 * So, objects of SplQueue also support methods push() and pop().
 * But beware, if you use push() and pop() methods on a SplQueue object,
 * it behaves like a stack(LIFO) rather than a queue(FIFO).
 */
class Scheduler
{
    protected $queue = null;

    public function __construct()
    {
        $this->queue = new SplQueue();
    }

    public function enqueue(Job $job)
    {
        $this->queue->enqueue($job); // put a Job object in the queue
    }

    public function run()
    {
        while (!$this->queue->isEmpty()) {
            $job = $this->queue->dequeue(); // Dequeues (removes) value from the front of the queue.
            $job->run(); // the dequeue'd value is a Job object, so we can use Job::run()

            if ($job->finished() === false) {
                $this->enqueue($job); // put unfinished job back in the back of the queue
            }
        }
    }
}
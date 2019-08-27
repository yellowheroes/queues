# queues
This demo shows how we can use generators as jobs that are paused and continued,
allowing us to run several jobs in a queue FIFO (using native PHP class SplQueue).
(original source: https://medium.com/async-php/co-operative-php-multitasking-ce4ef52858a0)

Class Job is a decorator for ordinary generators. We store the generator for later use,
and implement run() and finished() methods. run() makes the task tick,
while finished() lets the Scheduler know when to stop running the task.

Class Scheduler maintains a queue of running jobs.

Scheduler::run() will run all jobs until the queue is empty.
It takes a job from the front of the queue and runs it.
If the job has not finished after a run (i.e. the generator has not yet yielded all of its values yet), the job is sent back to the back of the queue.

SplQueue is ideal for this use case. It runs off a FIFO schedule, i.e. each job(generator) will get some processing time.

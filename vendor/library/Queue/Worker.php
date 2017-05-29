<?php 
namespace Skinny\Queue;

class Worker 
{
    public function __construct($manager)
    {

    }
    public function run($connection, $queue, $delay, $memory)
    {

    }

    /**
     * Listen to the given queue.
     *
     * @param  string  $connectionName
     * @param  string  $queue
     * @param  int     $delay
     * @param  int     $sleep
     * @param  int     $maxTries
     * @return array
     */
    public function pop($connectionName, $queue = null, $delay = 0, $sleep = 3, $maxTries = 0)
    {
        try {
            $connection = $this->manager->connection($connectionName);

            $job = $this->getNextJob($connection, $queue);

            // If we're able to pull a job off of the stack, we will process it and
            // then immediately return back out. If there is no job on the queue
            // we will "sleep" the worker for the specified number of seconds.
            if (! is_null($job)) {
                return $this->process(
                    $this->manager->getName($connectionName), $job, $maxTries, $delay
                );
            }
        } catch (Exception $e) {
            if ($this->exceptions) {
                $this->exceptions->report($e);
            }
        } catch (Throwable $e) {
            if ($this->exceptions) {
                $this->exceptions->report(new FatalThrowableError($e));
            }
        }

        $this->sleep($sleep);

        return ['job' => null, 'failed' => false];
    }

    /**
     * Get the next job from the queue connection.
     *
     * @param  \Illuminate\Contracts\Queue\Queue  $connection
     * @param  string  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    protected function getNextJob($connection, $queue)
    {
        if (is_null($queue)) {
            return $connection->pop();
        }

        foreach (explode(',', $queue) as $queue) {
            if (! is_null($job = $connection->pop($queue))) {
                return $job;
            }
        }
    }

    /**
     * Process a given job from the queue.
     *
     * @param  string  $connection
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  int  $maxTries
     * @param  int  $delay
     * @return array|null
     *
     * @throws \Throwable
     */
    public function process($connection, Job $job, $maxTries = 0, $delay = 0)
    {
        if ($maxTries > 0 && $job->attempts() > $maxTries) {
            return $this->logFailedJob($connection, $job);
        }

        try {
            // First we will fire off the job. Once it is done we will see if it will
            // be auto-deleted after processing and if so we will go ahead and run
            // the delete method on the job. Otherwise we will just keep moving.
            $job->fire();

            $this->raiseAfterJobEvent($connection, $job);

            return ['job' => $job, 'failed' => false];
        } catch (Exception $e) {
            // If we catch an exception, we will attempt to release the job back onto
            // the queue so it is not lost. This will let is be retried at a later
            // time by another listener (or the same one). We will do that here.
            if (! $job->isDeleted()) {
                $job->release($delay);
            }

            throw $e;
        } catch (Throwable $e) {
            if (! $job->isDeleted()) {
                $job->release($delay);
            }

            throw $e;
        }
    }

    /**
     * Log a failed job into storage.
     *
     * @param  string  $connection
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @return array
     */
    protected function logFailedJob($connection, Job $job)
    {
        if ($this->failer) {
            $this->failer->log($connection, $job->getQueue(), $job->getRawBody());

            $job->delete();

            $job->failed();

            $this->raiseFailedJobEvent($connection, $job);
        }

        return ['job' => $job, 'failed' => true];
    }

    /**
     * Determine if the memory limit has been exceeded.
     *
     * @param  int   $memoryLimit
     * @return bool
     */
    public function memoryExceeded($memoryLimit)
    {
        return (memory_get_usage() / 1024 / 1024) >= $memoryLimit;
    }

    /**
     * Stop listening and bail out of the script.
     *
     * @return void
     */
    public function stop()
    {
        die;
    }

    /**
     * Sleep the script for a given number of seconds.
     *
     * @param  int   $seconds
     * @return void
     */
    public function sleep($seconds)
    {
        sleep($seconds);
    }
}

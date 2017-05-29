<?php 
namespace Skinny\Queue;

use Closure;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;
use Symfony\Component\Process\PhpExecutableFinder;

class Listener
{
    protected $workerCommand;
    protected $commandPath;

    public function __construct($commandPath)
    {
        $this->commandPath = $commandPath;

        $this->workerCommand = $this->buildWorkerCommand();
    }
    /**
     * Build the environment specific worker command.
     *
     * @return string
     */
    protected function buildWorkerCommand()
    {
        $binary = ProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));
        $artisan = 'skinny';
        $command = 'queue:work %s --queue=%s --delay=%s --memory=%s --sleep=%s --tries=%s';

        return "{$binary} {$artisan} {$command}";
    }

    /**
     * Listen to the given queue connection.
     *
     * @param  string  $connection
     * @param  string  $queue
     * @param  string  $delay
     * @param  string  $memory
     * @param  int     $timeout
     * @return void
     */
    public function listen($connection, $queue, $delay, $memory, $timeout = 60)
    {
        $process = $this->makeProcess($connection, $queue, $delay, $memory, $timeout);

        while (true) {
            $this->runProcess($process, $memory);
        }
    }

    /**
     * Determine if the memory limit has been exceeded.
     *
     * @param  int  $memoryLimit
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
     * Listen to the given queue connection.
     *
     * @param  string  $connection
     * @param  string  $queue
     * @param  string  $delay
     * @param  string  $memory
     * @param  int     $timeout
     * @return void
     */
    public function listen($connection, $queue, $delay, $memory, $timeout = 60)
    {
        $process = $this->makeProcess($connection, $queue, $delay, $memory, $timeout);

        while (true) {
            $this->runProcess($process, $memory);
        }
    }

    /**
     * Create a new Symfony process for the worker.
     *
     * @param  string  $connection
     * @param  string  $queue
     * @param  int     $delay
     * @param  int     $memory
     * @param  int     $timeout
     * @return \Symfony\Component\Process\Process
     */
    public function makeProcess($connection, $queue, $delay, $memory, $timeout)
    {
        $string = $this->workerCommand;

        // Next, we will just format out the worker commands with all of the various
        // options available for the command. This will produce the final command
        // line that wee will pass into a Symfony process object for processing.
        $command = sprintf(
            $string,
            ProcessUtils::escapeArgument($connection),
            ProcessUtils::escapeArgument($queue),
            $delay,
            $memory,
            $this->sleep,
            $this->maxTries
        );

        return new Process($command, $this->commandPath, null, null, $timeout);
    }

    /**
     * Run the given process.
     *
     * @param  \Symfony\Component\Process\Process  $process
     * @param  int  $memory
     * @return void
     */
    public function runProcess(Process $process, $memory)
    {
        $process->run(function ($type, $line) {
            echo $type . $line . '\n';
        });

        // Once we have run the job we'll go check if the memory limit has been
        // exceeded for the script. If it has, we will kill this script so a
        // process manager will restart this with a clean slate of memory.
        if ($this->memoryExceeded($memory)) {
            $this->stop();
        }
    }
}
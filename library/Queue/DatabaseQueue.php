<?php 

namespace Skinny\Queue;

class DatabaseQueue
{
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $database;

    /**
     * The database table that holds the jobs.
     *
     * @var string
     */
    protected $table;

    /**
     * The name of the default queue.
     *
     * @var string
     */
    protected $default;

    /**
     * The expiration time of a job.
     *
     * @var int|null
     */
    protected $expire = 60;

    /**
     * Create a new database queue instance.
     *
     * @param  \Illuminate\Database\Connection  $database
     * @param  string  $table
     * @param  string  $default
     * @param  int  $expire
     * @return void
     */
    public function __construct($database, $table, $default = 'default', $expire = 60)
    {
        $this->table = $table;
        $this->expire = $expire;
        $this->default = $default;
        $this->database = $database;
    }


    public function push($job, $data = '', $queue = null)
    {
        return $this->pushToDatabase($queue, $this->createPayload($job, $data));
    }

    public function pop($queue = null)
    {

    }

    protected function createPayload($job, $data)
    {
        return json_encode(['job' => $job, 'data' => $data]);
    }

    /**
     * Push a raw payload to the database with a given delay.
     *
     * @param  \DateTime|int  $delay
     * @param  string|null  $queue
     * @param  string  $payload
     * @param  int  $attempts
     * @return mixed
     */
    protected function pushToDatabase($queue, $payload, $status = 1, $errReason = null)
    {
        $attributes = $this->buildDatabaseRecord(
            $this->getQueue($queue), $payload, $status, $errReason
        );

        $this->database()->insert($this->table, $attributes);

        return $this->database()->lastInsertId();
    }

    protected function buildDatabaseRecord($queue, $payload, $status = 1, $errReason = null, $attempts = 0)
    {
        return [
            'queue' => $queue,
            'payload' => $payload,
            'status' => $status,
            'updated' => time(),
            'errReason' => $errReason, // 失败原因
            'reserved' => 0, // 任务执行时间
            'attempts' => $attempts, // 执行次数
        ];
    }

    protected function database()
    {
        return $this->database;
    }

    /**
     * Get the queue or return the default.
     *
     * @param  string|null  $queue
     * @return string
     */
    protected function getQueue($queue)
    {
        return $queue ?: $this->default;
    }
}

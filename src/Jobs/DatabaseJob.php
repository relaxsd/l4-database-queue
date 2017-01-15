<?php

namespace Relaxsd\Queue\Jobs;

use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\Job;
use Relaxsd\Queue\DatabaseQueue;

class DatabaseJob extends Job
{
    /**
     * The database queue instance.
     *
     * @var \Relaxsd\Queue\DatabaseQueue
     */
    protected $database;

    /**
     * The database job payload.
     *
     * @var \StdClass
     */
    protected $job;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Container\Container $container
     * @param  \Relaxsd\Queue\DatabaseQueue    $database
     * @param  \StdClass                       $job
     * @param  string                          $queue
     */
    public function __construct(Container $container, DatabaseQueue $database, $job, $queue)
    {
        $this->job       = $job;
        $this->queue     = $queue;
        $this->database  = $database;
        $this->container = $container;
    }

    /**
     * Fire the job.
     *
     * @return void
     */
    public function fire()
    {
        $this->resolveAndFire(json_decode($this->getRawBody(), true));
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->job->payload;
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();

        $this->database->deleteReserved($this->queue, $this->job->id);
    }

    /**
     * Release the job back into the queue.
     *
     * @param  int $delay
     * @return void
     */
    public function release($delay = 0)
    {
        parent::release($delay);

        $this->delete();

        $this->database->release($this->queue, $this->job, $delay);
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return (int)$this->job->attempts;
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->job->id;
    }

    /**
     * Get the IoC container instance.
     *
     * @return \Illuminate\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get the underlying queue driver instance.
     *
     * @return \Relaxsd\Queue\DatabaseQueue
     */
    public function getDatabaseQueue()
    {
        return $this->database;
    }

    /**
     * Get the underlying database job.
     *
     * @return \StdClass
     */
    public function getDatabaseJob()
    {
        return $this->job;
    }
}

<?php

namespace Relaxsd\Queue\Connectors;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Relaxsd\Queue\DatabaseQueue;
use Illuminate\Support\Arr;

class DatabaseConnector implements ConnectorInterface
{
    /**
     * Database connections.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $connections;

    /**
     * Create a new connector instance.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface $connections
     */
    public function __construct(ConnectionResolverInterface $connections)
    {
        $this->connections = $connections;
    }

    /**
     * Establish a queue connection.
     *
     * @param  array $config
     * @return \Illuminate\Queue\QueueInterface
     */
    public function connect(array $config)
    {
        return new DatabaseQueue(
            $this->connections->connection(Arr::get($config, 'connection')),
            $config['table'],
            $config['queue'],
            Arr::get($config, 'retry_after', 60)
        );
    }
}

<?php

namespace App\Queue;

use App\Task\TaskHandler;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueService
{
    protected AMQPStreamConnection $connection;
    protected AMQPChannel $channel;

    protected string $host;
    protected int $port;
    protected string $user;
    protected string $password;
    protected string $vhost;
    protected string $queue_name;

    public function __construct(
        string $host,
        int $port,
        string $user,
        string $password,
        string $vhost,
        string $queue_name
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->vhost = $vhost;
        $this->queue_name = $queue_name;

    }

    public function connect()
    {
        $this->connection = new AMQPStreamConnection(
            $this->host, $this->port, $this->user, $this->password, $this->vhost, $this->queue_name
        );
    }

    public function consume(int $consumeCount)
    {
        date_default_timezone_set('Europe/Moscow');

        $this->channel = $this->connection->channel();

        $this->channel->queue_declare(
            $this->queue_name, false, true, false, false
        );

        $this->channel->basic_qos(null, 1, null);


        $this->channel->basic_consume(
            $this->queue_name, $consumeCount, false, false, false, false,
            function ($msg) {

                $taskHandler = new TaskHandler();
                $taskHandler->handle($msg->body);
                file_put_contents(__DIR__ . '/../../resources/log.log', $date = date('Y-m-d H:i:s') . ' = ' . $msg->body . "\n", FILE_APPEND);
                $msg->ack();
            });

        while ($this->channel->is_open()) {
            $this->channel->wait();
        }
    }

    public function publishMessage($data): bool
    {
        $msg = new AMQPMessage(
            $data,
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(
            $this->queue_name, false, true, false, false
        );

        $this->channel->basic_publish($msg, '', $this->queue_name);
        return true;
    }

    public function closeConnection()
    {
        if ($this->channel) {
            $this->channel->close();
            $this->connection->close();
        }
    }
}
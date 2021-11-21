<?php

namespace App\TaskHandler;


use App\RabbitMq\RabbitMq;
use Dotenv\Dotenv;
use Exception;

class TaskHandler
{
    protected array $data = [1,2,3];
    protected $queueService;

    public function __construct()
    {

    }


    /**
     * @throws Exception
     */
    public function run()
    {
        $rabbitMq = new RabbitMq(
            getenv('RABBIT_HOST'),
            getenv('RABBIT_PORT'),
            getenv('RABBIT_USER'),
            getenv('RABBIT_PASSWORD'),
            getenv('RABBIT_VHOST'),
            getenv('RABBIT_QUEUE_NAME')
        );


        $rabbitMq->consume(getenv('RABBIT_QUEUE_NAME'), '1');
        $rabbitMq->consume(getenv('RABBIT_QUEUE_NAME'), '2' );


        var_dump($rabbitMq->publishMessage('hellooo', getenv('RABBIT_QUEUE_NAME')));
        var_dump($rabbitMq->publishMessage('hellooo@@@222', getenv('RABBIT_QUEUE_NAME')));
        var_dump($rabbitMq->publishMessage('hellooo@@@2221231231230___', getenv('RABBIT_QUEUE_NAME')));


        sleep(5);
        $rabbitMq->closeConnections();
        return 'running!';
    }
}
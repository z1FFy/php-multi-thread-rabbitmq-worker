<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Queue\RabbitMqService;
use Dotenv\Dotenv;
use App\TaskHandler\TaskHandler;


$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();



$commandAction = @$argv[1];

$rabbitMq = new RabbitMqService(
    getenv('RABBIT_HOST'),
    getenv('RABBIT_PORT'),
    getenv('RABBIT_USER'),
    getenv('RABBIT_PASSWORD'),
    getenv('RABBIT_VHOST'),
    getenv('RABBIT_QUEUE_NAME')
);

switch ($commandAction) {

    case 'consume':
        $rabbitMq->consume();
        break;

    case 'run_tasks':
        $rabbitMq->publishMessage('hellooo');
        $rabbitMq->publishMessage('hellooo@@@222');
        $rabbitMq->publishMessage('hellooo@@@2221231231230___');
        $rabbitMq->publishMessage('hellooo');
        $rabbitMq->publishMessage('hellooo@@@222');
        $rabbitMq->publishMessage('hellooo@@@2221231231230___');
        $rabbitMq->publishMessage('hellooo');
        $rabbitMq->publishMessage('hellooo@@@222');
        $rabbitMq->publishMessage('hellooo@@@2221231231230___');
        $rabbitMq->publishMessage('hellooo');
        $rabbitMq->publishMessage('hellooo@@@222');
        $rabbitMq->publishMessage('hellooo@@@2221231231230___');

        break;
}

$rabbitMq->closeConnections();



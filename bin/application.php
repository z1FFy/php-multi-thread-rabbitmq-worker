<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Queue\QueueService;
use Dotenv\Dotenv;
use Spatie\Async\Pool;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

$commandAction = @$argv[1];

switch ($commandAction) {


    case 'consume':
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        $pool = Pool::create();

        $consumeNumber = getenv('RABBIT_CONSUME_NUMBER');
        for ($i = 1; $i <= $consumeNumber; $i++) {

            $pool->add(function () use ($i) {
                $rabbitMqService = new QueueService(
                    getenv('RABBIT_HOST'),
                    getenv('RABBIT_PORT'),
                    getenv('RABBIT_USER'),
                    getenv('RABBIT_PASSWORD'),
                    getenv('RABBIT_VHOST'),
                    getenv('RABBIT_QUEUE_NAME')
                );
                $rabbitMqService->connect();

                $rabbitMqService->consume($i);

                $rabbitMqService->closeConnection();
            })->then(function ($output) {
                // Handle success
            })->catch(function (Throwable $exception) {
                // Handle exception
            });
        }

        $pool->wait();

        break;

    case 'execute_tasks':
        file_put_contents(__DIR__ . '/../resources/log.log', "======= START ====== \n", FILE_APPEND);

        $tasks = file_get_contents(__DIR__ . '/../resources/tasks.json');
        $tasks = json_decode($tasks, 1);

        $rabbitMqService = new QueueService(
            getenv('RABBIT_HOST'),
            getenv('RABBIT_PORT'),
            getenv('RABBIT_USER'),
            getenv('RABBIT_PASSWORD'),
            getenv('RABBIT_VHOST'),
            getenv('RABBIT_QUEUE_NAME')
        );

        $rabbitMqService->connect();
        foreach ($tasks as $task) {
            $task = json_encode($task);
            $rabbitMqService->publishMessage($task);
        }


        $rabbitMqService->closeConnection();

        break;
}



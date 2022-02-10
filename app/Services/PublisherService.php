<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class PublisherService
{

    public function publishMessage(string $title){

        Log::debug("Sending new job ${title} to log.");
        
        $connection = new AMQPStreamConnection(
            config('rabbitmq.connection.host'),
            config('rabbitmq.connection.port'), 
            config('rabbitmq.connection.user'), 
            config('rabbitmq.connection.password'), 
            config('rabbitmq.connection.vh')
        );
        $channel = $connection->channel();
        $queue = 'notification-queue';
        $exchange = 'router';

        /*
            name: $queue
            passive: false
            durable: true // the queue will survive server restarts
            exclusive: false // the queue can be accessed in other channels
            auto_delete: false //the queue won't be deleted once the channel is closed.
        */
        $channel->queue_declare($queue, false, false, false, false);

        /*
            name: $exchange
            type: direct
            passive: false
            durable: true // the exchange will survive server restarts
            auto_delete: false //the exchange won't be deleted once the channel is closed.
        */
        $channel->exchange_declare($exchange, 'fanout', false, false, false);
        $channel->queue_bind($queue, $exchange);

        $msg = new AMQPMessage($title);
        $channel->basic_publish($msg, '', $queue);

        $channel->close();
        $connection->close();
    }

}
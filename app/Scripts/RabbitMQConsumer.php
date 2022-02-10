<?php

require_once dirname(dirname(__DIR__,1)) . '/vendor/autoload.php';

use PhpAmqpLib\Exchange\AMQPExchangeType;
use App\Services\Notification\SMSNotification;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Services\Notification\EmailNotification;
use Illuminate\Support\Facades\Log;

//env settings should not be here
define('HOST', 'challenge-rabbitmq');
define('PORT', 5672);
define('USER', 'guest');
define('PASSWORD', 'guest');
define('VH', 'local-vh');

$queue = 'notification-queue';
$exchange = 'router';
$consumerTag = 'consumer' . getmypid();

$connection = new AMQPStreamConnection(HOST, PORT, USER, PASSWORD, VH);
$channel = $connection->channel();

/*
    name: $queue    // should be unique in fanout exchange.
    passive: false  // don't check if a queue with the same name exists
    durable: false // the queue will not survive server restarts
    exclusive: false // the queue might be accessed by other channels
    auto_delete: true //the queue will be deleted once the channel is closed.
*/
$channel->queue_declare($queue, false, false, false, false);

/*
    name: $exchange
    type: direct
    passive: false // don't check if a exchange with the same name exists
    durable: false // the exchange will not survive server restarts
    auto_delete: true //the exchange will be deleted once the channel is closed.
*/

$channel->exchange_declare($exchange, AMQPExchangeType::FANOUT, false, false, false);

$channel->queue_bind($queue, $exchange);

/**
 * @param \PhpAmqpLib\Message\AMQPMessage $message
 */
function process_message($message)
{
    echo "\nMessage Received: " . $message->body . "\n";
    echo "Receiving notification message in broker. Tile: " . $message->body. "\n";
    //maybe its better have 2 diff queues to make retries easier
    try{
        $emailNotification = new EmailNotification();
        $emailNotification->send($message->body);

        $smsNotification = new SMSNotification();
        $smsNotification->send($message->body);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

   


    $message->ack();

    // Send a message with the string "quit" to cancel the consumer.
    if ($message->body === 'quit') {
        $message->getChannel()->basic_cancel($message->getConsumerTag());
    }
}

/*
    queue: Queue from where to get the messages
    consumer_tag: Consumer identifier
    no_local: Don't receive messages published by this consumer.
    no_ack: If set to true, automatic acknowledgement mode will be used by this consumer. See https://www.rabbitmq.com/confirms.html for details.
    exclusive: Request exclusive consumer access, meaning only this consumer can access the queue
    nowait: don't wait for a server response. In case of error the server will raise a channel
            exception
    callback: A PHP Callback
*/

$channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');

/**
 * @param \PhpAmqpLib\Channel\AMQPChannel $channel
 * @param \PhpAmqpLib\Connection\AbstractConnection $connection
 */
function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}

register_shutdown_function('shutdown', $channel, $connection);

// Loop as long as the channel has callbacks registered
while ($channel->is_consuming()) {
    $channel->wait();
}
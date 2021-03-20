<?php
namespace common\models;
require_once __DIR__ . '/../../vendor/autoload.php';
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQ
{
    private $host = 'VisorRabbitMQ';
    private $port = 5672;
    private $user = 'guest';
    private $password = 'guest';
    private $connection;
    private $channel;

    private function openConnection($queueName)
    {
        $this->connection = new AMQPStreamConnection($this->host, $this->port , $this->user, $this->password, '/', false, 'AMQPLAIN',null, 'en:US', 60, 60, null, false, 30);
        $this->channel = $this->connection->channel();        
        $this->channel->queue_declare($queueName, false, false, false, false);
    }

    private function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function publishMessage($queueName, $message)
    {       
        $this->openConnection($queueName);
        $amqpMessage = new AMQPMessage(json_encode($message));
        $this->channel->basic_publish($amqpMessage, '', $queueName);
        $this->closeConnection();
    }

    public function publishMessageList($queueName, $messageList)
    {
        $this->openConnection($queueName);        
        foreach($messageList as $message)
        {
            $amqpMessage = new AMQPMessage(json_encode($message));
            $this->channel->basic_publish($amqpMessage, '', $queueName);
        }
        $this->closeConnection();
    }

    public function getMessage($queueName, $callback)
    {
        $this->openConnection($queueName);
        $prefetchSize = null;    // message size in bytes or null, otherwise error
        $prefetchCount = 1;      // prefetch count value
        $applyPerChannel = null; // can be false or null, otherwise error
        $this->channel->basic_qos($prefetchSize, $prefetchCount, $applyPerChannel);
        $this->channel->basic_consume($queueName, '', false, false, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
        $this->closeConnection();
    }

}
?>
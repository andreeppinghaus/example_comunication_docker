<?php

// Configurações do RabbitMQ
$queue = 'process_queue';
$connection = new AMQPConnection([
    'host' => 'rabbitmq',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest'
]);

// Conecta ao RabbitMQ
$connection->connect();
$channel = new AMQPChannel($connection);
$queueObj = new AMQPQueue($channel);
$queueObj->setName($queue);
$queueObj->declareQueue();

echo "Aguardando mensagens do RabbitMQ...\n";

// Consome a mensagem da fila
$queueObj->consume(function (AMQPEnvelope $envelope) use ($queueObj) {
    $message = json_decode($envelope->getBody(), true);
    echo "Mensagem recebida do RabbitMQ: " . print_r($message, true) . "\n";

    if ($message['status'] === 'success') {
        echo "Node.js terminou com sucesso! Continuando o processo PHP...\n";
        // Aqui você pode continuar o processo PHP
    } else {
        echo "Node.js falhou!\n";
    }

    // Confirma o recebimento da mensagem
    $queueObj->ack($envelope->getDeliveryTag());
    return false; // Para de consumir após receber a mensagem
});

// Fecha a conexão
$connection->disconnect();
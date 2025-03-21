<?php

// Configurações do RabbitMQ
$queue = 'process_queue';
$connection = new AMQPConnection([
    'host' => 'rabbitmq',
    'port' => 5672,
    'login' => 'meu_usuario',  // Usuário personalizado
    'password' => 'minha_senha'  // Senha personalizada
]);

echo "Aguardando RabbitMQ ficar disponível...\n";

// Tenta conectar ao RabbitMQ com um limite de tentativas
$maxAttempts = 10;
$attempt = 0;

while ($attempt < $maxAttempts) {
    try {
        $connection->connect();
        echo "Conectado ao RabbitMQ!\n";
        break;
    } catch (AMQPConnectionException $e) {
        $attempt++;
        echo "Tentativa $attempt de $maxAttempts: RabbitMQ não está disponível. Tentando novamente em 5 segundos...\n";
        sleep(5);
    }
}

if ($attempt === $maxAttempts) {
    die("Não foi possível conectar ao RabbitMQ após $maxAttempts tentativas.\n");
}

// Conecta ao RabbitMQ
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
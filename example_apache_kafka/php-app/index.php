<?php

/**
 * Biblioteca
 */
$maxAttempts = 3;
$attempt = 0;
$connected = false;

sleep(10);

while ($attempt < $maxAttempts && !$connected) {
    $attempt++;
    echo "Tentativa $attempt de $maxAttempts...\n";

    try {
        $conf = new RdKafka\Conf();
        $conf->set('group.id', 'php-consumer');

        // Configura o broker do Kafka
        $conf->set('metadata.broker.list', 'kafka:9092');

        $consumer = new RdKafka\KafkaConsumer($conf);

        // Inscreve no tópico
        $consumer->subscribe(['process_queue']);
        echo "Conectado ao Kafka!\n";
        $connected = true;

        echo "Aguardando mensagens do Kafka...\n";

        while (true) {
            $message = $consumer->consume(120 * 1000); // Timeout de 120 segundos

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $payload = json_decode($message->payload, true);
                    echo "Mensagem recebida do Kafka: " . print_r($payload, true) . "\n";

                    if ($payload['status'] === 'success') {
                        echo "Node.js terminou com sucesso! Continuando o processo PHP...\n";
                        // Aqui você pode continuar o processo PHP
                    } else {
                        echo "Node.js falhou!\n";
                    }
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "Não há mais mensagens.\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "Timeout ao aguardar mensagens.\n";
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }
    } catch (\Exception $e) {
        echo "Erro ao conectar ao Kafka: " . $e->getMessage() . "\n";

        if ($attempt === $maxAttempts) {
            echo "Número máximo de tentativas atingido. Abortando...\n";
            exit(1);
        }

        echo "Tentando novamente em 5 segundos...\n";
        sleep(5); // Aguarda 5 segundos
    }
}
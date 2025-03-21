# Comunicação entre containers
Montagem de dois containers um com um script em Node.js e outro com um script em PHP para que o Node.js publique uma mensagem em uma fila do RabbitMQ quando terminar seu processamento, e o PHP consuma essa mensagem para continuar o processo.

```
meu-projeto/
│
├── node-app/
│   ├── Dockerfile
│   ├── package.json
│   └── app.js
│
├── php-app/
│   ├── Dockerfile
│   └── index.php
│
├── docker-compose.yml
```

## Execução do container
No diretório raiz do projeto (meu-projeto/), execute o seguinte comando para construir e iniciar os containers:
docker-compose up --build

## Explicação do Fluxo

1. O container node-app executa o script app.js, que simula um processo demorado.

2. Após 5 segundos, o Node.js publica uma mensagem na fila process_queue do RabbitMQ.

3. O container php-app consome a mensagem da fila e continua o processamento.

## Testando

Acesse a interface de gerenciamento do RabbitMQ em http://localhost:15672 (usuário: guest, senha: guest).

Verifique se a fila process_queue foi criada e se a mensagem foi publicada e consumida.

Verifique os logs dos containers para confirmar o fluxo:

docker logs node-app
docker logs php-app